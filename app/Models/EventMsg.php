<?php

namespace App\Models;

use App\Plugin\WxHelp;
use Illuminate\Database\Eloquent\Model;

class EventMsg extends Model
{
    protected $table = 'event_msgs';
    //主键ID
    protected $primaryKey = 'id';

    /**
     * 获取消息xml
     * @param $msg_id
     * @param $postObj
     * @return string
     */
    public static function getMsgXml($msg_id, $postObj)
    {
        $result = '';
        $item   = self::find($msg_id);
        switch ($item->event_code) {
            case 'text':
                $msg = str_replace('\n', "\n", $item->desc);
                $result = self::text($postObj, $msg);
                break;
            case 'news':
                $result = self::news($postObj, $item);
                break;
            case 'link':
                $result = self::link($postObj, $item->title, $item->url, $item->desc);
                break;
        }
        return $result;
    }

    /**
     * 图文
     * @param $postObj
     * @param $item
     * @return string
     */
    private static function news($postObj, $item)
    {
        $tplItem
                 = '<item>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>';
        $itemXml = sprintf($tplItem, $item->title, $item->desc, $item->pic_url, $item->url);
        $childs  = self::where('parent_id', $item->id)->get();
        $count   = 1;
        if ($childs) {
            foreach ($childs as $child) {
                $count ++;
                $itemXml .= sprintf($tplItem, $child->title, $child->desc, $child->pic_url, $child->url);
            }
        }
        $tpl
                   = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<ArticleCount>%s</ArticleCount>
<Articles>
%s
</Articles>
</xml>';
        $resultStr = sprintf($tpl, $postObj->FromUserName, $postObj->ToUserName, time(), 'news', $count, $itemXml);
        return $resultStr;
    }

    /**
     * 文本
     * @param $postObj
     * @param $content
     * @return string
     */
    private static function text($postObj, $content)
    {
        $tpl
                   = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>";
        $resultStr = sprintf($tpl, $postObj->FromUserName, $postObj->ToUserName, time(), 'text', $content);
        return $resultStr;
    }

    /**
     * 链接
     * @param $postObj
     * @param $title
     * @param $url
     * @param $desc
     * @return string
     */
    private static function link($postObj, $title, $url, $desc)
    {
        $tpl
                   = '<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<Url><![CDATA[%s]]></Url>
</xml>';
        $resultStr = sprintf($tpl, $postObj->FromUserName, $postObj->ToUserName, time(), 'link', $title, $desc, $url);
        return $resultStr;
    }
}
