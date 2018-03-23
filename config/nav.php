<?php
/**
 * 菜单配置
 * User: Administrator
 * Date: 2018/2/1
 * Time: 16:02
 */
return [
    'menuList' => [
        [
            "children" => null,
            "icon"     => "fa fa-cog",
            "name"     => "系统设置",
            "type"     => 0,
            "url"      => null,
            "list"     => [
                [
                    "children" => null,
                    "icon"     => "fa fa-cog",
                    "list"     => null,
                    "name"     => "用户管理",
                    "type"     => 1,
                    "url"      => '/user/list'
                ],
            ],
        ],
        [
            "children" => null,
            "icon"     => "fa fa-wechat",
            "name"     => "微信设置",
            "type"     => 0,
            "url"      => null,
            "list"     => [
                [
                    "children" => null,
                    "icon"     => "fa fa-font",
                    "list"     => null,
                    "name"     => "消息管理",
                    "type"     => 1,
                    "url"      => "/msg/list"
                ],
                [
                    "children" => null,
                    "icon"     => "fa fa-font",
                    "list"     => null,
                    "name"     => "事件管理",
                    "type"     => 1,
                    "url"      => "event/list"
                ],
                [
                    "children" => null,
                    "icon"     => "fa fa-font",
                    "list"     => null,
                    "name"     => "菜单配置",
                    "type"     => 1,
                    "url"      => "/menu/list"
                ],
            ],
        ],
        [
            "children" => null,
            "icon"     => "fa fa-qrcode",
            "name"     => "渠道管理",
            "type"     => 0,
            "url"      => null,
            "list"     => [
                [
                    "children" => null,
                    "icon"     => "fa fa-font",
                    "list"     => null,
                    "name"     => "二维码管理",
                    "type"     => 1,
                    "url"      => "/qr/list"
                ],
            ],
        ]
    ]
];