<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

class Users extends BaseModels
{
    protected $table = 'users';
    //主键ID
    protected $primaryKey = 'id';


    /**
     * 设置session
     * @param $user
     * @return bool
     */
    public static function setSession($user)
    {
        if(!$user) {
            return false;
        }
        session([
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
    }

}
