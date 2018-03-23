<?php

namespace App\Http\Controllers;

use App\Models\EventMsg;
use App\Models\Events;
use App\Models\QrCodes;
use App\Models\QrStat;
use App\Plugin\WxHelp;

class WxController extends Controller
{
    public function index()
    {
        $postStr   = $this->request->getContent();
        $signature = $this->request->input('signature');
        $timestamp = $this->request->input('timestamp');
        $nonce     = $this->request->input('nonce');
        $token     = config('wx.token');
        //非法验证
        $checkFlag = WxHelp::checkSignature($signature, $timestamp, $nonce, $token);
        if (!$checkFlag) {
            return $this->setJson(1, 'verification failure');
        }
        //首次验证
        if (empty($postStr)) {
            $msg = $this->request->input('echostr');
            return response($msg)->header('Content-type', 'text');
        }
        libxml_disable_entity_loader(true);
        $postObj      = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;//发送方帐号（一个OpenID）
        $toUsername   = $postObj->ToUserName;//开发者微信号
        $keyword      = trim($postObj->Content);//内容
        $msgType      = $postObj->MsgType;//类型
        $Event        = $postObj->Event;
        $EventKey     = $postObj->EventKey;
        $MsgId        = $postObj->MsgId;//消息唯一ID
        $result       = '';
        mylog($postObj, 'postObj');
        switch ($msgType) {
            case 'text':
                $item = Events::where('type', 'keywords')->where('content', $keyword)->orderBy('created_at', 'desc')->first();
                if ($item) {
                    $result = EventMsg::getMsgXml($item->msg_id, $postObj);
                } else {
                    $any    = Events::where('type', 'keywords')->where('content', '*')->orderBy('created_at', 'desc')->first();
                    $result = EventMsg::getMsgXml($any->msg_id, $postObj);
                }
                break;
            case 'event':
                switch (strtolower($Event)) {
                    case 'click':
                        $item = Events::where('type', 'click')->where('content', $EventKey)->first();
                        if ($item) {
                            $result = EventMsg::getMsgXml($item->msg_id, $postObj);
                        }
                        break;
                    case 'subscribe':
                        $item = Events::where('type', 'subscribe')->orderBy('created_at', 'desc')->first();
                        if ($item) {
                            $result = EventMsg::getMsgXml($item->msg_id, $postObj);
                        }
                        if (isset($EventKey) && strpos($EventKey, 'qrscene_') !== false) {//关注量
                            $ticket    = $postObj->Ticket;
                            $scene_str = substr($EventKey, 8);
                            $qr        = QrCodes::where('scene_str', $scene_str)->where('is_use', 1)->first();
                            if ($qr) {
                                //判断临时二维码是否过期
                                $expired = $qr->day * 24 * 3600 + strtotime($qr->created_at);
                                if ($qr->type == 'QR_STR_SCENE' && time() < $expired) {//过期不统计
                                    QrStat::record($fromUsername, $qr->id);
                                } else if ($qr->type == 'QR_LIMIT_STR_SCENE') {//永久
                                    QrStat::record($fromUsername, $qr->id);
                                }
                            }
                        } else {//扫描量
                            $ticket = $postObj->Ticket;
                            $qr     = QrCodes::where('ticket', $ticket)->where('is_use', 1)->first();
                            if ($qr) {
                                //判断临时二维码是否过期
                                $expired = $qr->day * 24 * 3600 + strtotime($qr->created_at);
                                if ($qr->type == 'QR_STR_SCENE' && time() < $expired) {//过期不统计
                                    QrStat::record($fromUsername, $qr->id);
                                } else if ($qr->type == 'QR_LIMIT_STR_SCENE') {//永久
                                    QrStat::record($fromUsername, $qr->id);
                                }
                            }
                        }
                        break;
                    case 'unsubscribe':
                        $item = Events::where('type', 'unsubscribe')->orderBy('created_at', 'desc')->first();
                        if ($item) {
                            $result = EventMsg::getMsgXml($item->msg_id, $postObj);
                        }
                        break;
                    case 'scan':
                        break;
                    default:
                        break;
                }
                break;
            default :
                break;
        }
        return $result;
    }

    public function auth()
    {
        $redirect_url = route('openid');
        WxHelp::weChatRedirect($redirect_url);
    }

    public function openid()
    {
        $code = $this->request->input('code');
        $res  = WxHelp::getAuthAccessToken($code);
        dump($res);
    }
}
