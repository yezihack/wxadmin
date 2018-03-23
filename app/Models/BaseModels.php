<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModels extends Model
{
    protected $table = '';
    //主键ID
    protected $primaryKey = '';
    //是否允许批量修改
    protected $fillable = [];
    //相当于黑名单
    protected $guarded = [];
    //自动维护时间字段
    public $timestamps = true;
}
