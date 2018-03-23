<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCodes extends Model
{
    protected $table = 'qr_codes';
    //主键ID
    protected $primaryKey = 'id';

    public static $type_list
        = [
            'QR_STR_SCENE'       => '临时二维码',
            'QR_LIMIT_STR_SCENE' => '永久二维码',
        ];
    
    /**
     * 获取唯一的str
     * @return string
     */
    public static function getOnlyStr()
    {
        $scene_str = str_random(mt_rand(16, 64));
        $count     = QrCodes::where('scene_str', $scene_str)->count();
        if ($count > 0) {
            return self::getOnlyStr();
        }
        return $scene_str;
    }

}
