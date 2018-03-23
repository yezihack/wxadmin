<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Menus extends Model
{
    protected $table = 'menus';
    //主键ID
    protected $primaryKey = 'id';

    public static $type_list
        = [
            'view'  => '跳转链接',
            'click' => '按扭事件',
        ];

    public static function get_list()
    {
        $data = self::orderBy('weight', 'desc')->get();
        $ones = [];
        //获取一级菜单
        foreach ($data as $item) {
            if ($item->parent_id > 0) {
                continue;
            }
            $ones[$item->id] = $item->toArray();
        }
        $data_arr = $data->toArray();
        $list     = [];
        foreach ($ones as $one) {
            $list[] = $one;
            $two    = [];
            foreach ($data_arr as $key => $item) {
                if ($one['id'] != $item['parent_id']) {
                    continue;
                }
                $item['name'] = '├──' . $item['name'];
                $two[]        = $item;
            }
            $list = array_merge($list, $two);
        }
        foreach ($list as $key => $item) {
//            $list[$key]['update_format']    = Carbon::parse($item['created_at'])->diffForHumans();
            $list[$key]['update_format']    = Carbon::parse($item['created_at'])->format('Y-m-d');
            $list[$key]['parent_id_format'] = $item['parent_id'] > 0 ? '子菜单' : '一级菜单';
            $list[$key]['type_format']      = isset(self::$type_list[$item['type']]) ? self::$type_list[$item['type']] : '';
        }
        return $list;
    }
}
