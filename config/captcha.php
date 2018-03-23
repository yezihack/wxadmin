<?php
/**
 * http://laravelacademy.org/post/3910.html
如果要返回原生图片，可以调用这个函数：
captcha();
或者
Captcha::create();
如果要返回URL：
captcha_src();
或者
Captcha::src();
如果要返回HTML：
captcha_img();
我们这个示例中使用的就是这个函数，或者调用Captcha门面上的方法：
Captcha::img();
要使用配置文件captcha.php中不同的配置项，可以这样调用：
captcha_img('flat');
Captcha::img('inverse');
 */
return [

    'characters' => '2346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ',

    'default'   => [
        'length'    => 4,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
    ],

    'flat'   => [
        'length'    => 6,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 90,
        'lines'     => 6,
        'bgImage'   => false,
        'bgColor'   => '#ecf2f4',
        'fontColors'=> ['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast'  => -5,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 120,
        'height'    => 36,
        'quality'   => 90,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 2,
        'invert'    => true,
        'contrast'  => -5,
    ]

];
