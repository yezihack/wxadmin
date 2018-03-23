<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $table = 'events';
    //主键ID
    protected $primaryKey = 'id';
    /**类型定义*/
    public static $type_list
        = [
            'keywords'    => '关键字回复',
            'subscribe'   => '订阅回复',
            'unsubscribe' => '取消订阅回复',
            'scan'        => '扫描事件回复',
            'click'       => '事件回复',
        ];

    /**
     * 获取关联的click事件
     * @return array|bool
     */
    public static function getJoinClickEvent()
    {
        $data = self::where('is_use', 1)->where('type', 'click')->get();
        if ($data) {
            $list = [];
            foreach ($data as $item) {
                $list[$item->content] = EventMsg::where('id', $item->msg_id)->value('name') . '(' . $item->content . ')';
            }
            return $list;
        }
        return false;
    }
}
