<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 16:55
 */
define('SGLOGS_PATH', str_replace('\\', '/', dirname(__DIR__)) . '/' . 'public/logs/');

if (!function_exists('mylog')) {
    /**
     * 写日志
     * @param $data
     * @param string $flag
     * @param bool $is
     * @param string $title
     */
    function mylog($data, $flag = 'None', $is = false, $title = '断点日志')
    {
        return \App\Plugin\SgLogs::write($data, $flag, $is, 'debug', $title);
    }
}
if (!function_exists('setResult')) {
    /**
     * 返回
     * @param $status
     * @param $msg
     * @param string $data
     * @return array
     */
    function setResult($status, $msg, $data = '')
    {
        $content = [
            'status' => $status,
            'msg'    => $msg,
            'data'   => $data,
        ];
        return $content;
    }
}
if (!function_exists('mydump')) {

    function mydump()
    {
        $s = func_get_args();
        ob_start();
        echo '<pre>';
        foreach ($s as $item) {
            var_dump($item);
        }
        echo '</pre>';
        $content = ob_get_contents();
        ob_clean();
        echo $content;
    }
}