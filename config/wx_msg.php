<?php
/**
 * 微信模板消息
 * User: Administrator
 * Date: 2018/3/13
 * Time: 16:44
 */
return [
    'new_notice' => array( //你好,你已兼职申请成功!
        'touser' => '%s',
        'template_id' => 'Vtar_TyaVVsHO2XDrfAauhhFyONnuVsQn9EZf5Qykew',
        'url' => '%s',
        'topcolor' => '#FF0000',
        'data' => array(
            'first' => array(
                'value' => '%s',
                'color' => '#173177',
            ),
            'keyword1' => array(//申请职位
                'value' => '%s',
                'color' => '#173177',
            ),//招聘企业
            'keyword2' => array(
                'value' => '%s',
                'color' => '#173177',
            ),     //姓名
            'keyword3' => array(
                'value' => '%s',
                'color' => '#173177',
            ),     //电话
            'keyword4' => array(
                'value' => '%f',
                'color' => '#173177',
            ),     //时间
            'keyword5' => array(
                'value' => '%s',
                'color' => '#173177',
            ),     //备注
            'remark' => array(
                'value' => '请保持电话畅通,耐心等待企业或人力资源公司通知！',
                'color' => '#173177',
            )
        ),
    ),
];