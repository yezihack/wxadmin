<?php

namespace App\Http\Controllers;

use App\Plugin\WxHelp;

class TemplateController extends Controller
{
    public function notice()
    {
        $openid   = 'x1';
        $url      = '2';
        $title    = '3';
        $job      = '4';
        $company  = '5';
        $username = '6';
        $mobile   = '7';
        $date     = '8';
        $template = config('wx_msg.new_notice');
        $xml      = WxHelp::arrToXml($template);
        $xml_str  = sprintf($xml, $openid, $url, $title, $job, $company, $username, $mobile, $date);
        $obj      = WxHelp::xmlToObj($xml_str);
        $json     = json_encode($obj);
        $result = WxHelp::sendTemplateInfo($json);
        dump($result);
    }
}
