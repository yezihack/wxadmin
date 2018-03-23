<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('type', '50')->default('')->comment('二维码类型');
            $table->char('scene_str', 64)->default('')->comment('场景值ID（字符串形式的ID），字符串类型，长度限制为1到64');
            $table->string('ticket')->default('')->comment('ticket可以获取二维码');
            $table->smallInteger('day')->default(0)->comment('有效时间/天');
            $table->string('url')->default('')->comment('二维码解析后的url地址');
            $table->string('img_src')->default('')->comment('二维码路径');
            $table->tinyInteger('is_use')->default(0)->comment('是否在使用,1使用中.0停用');
            $table->string('remark')->default('')->comment('备注说明');
            $table->timestamps();
        });
    }

    public function data()
    {
//        insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('1','1','New openning Hoardings','http://weixin.qq.com/q/02N1pyl6sXfk310000w07Q','1492507087');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('2','2','HR activities','http://weixin.qq.com/q/02NiQ2k5sXfk310000007-','1492507341');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('3','3','Poster FOR employees','http://weixin.qq.com/q/02z_-jl6sXfk310000M074','1492507271');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('4','4','Recruitment materials','http://weixin.qq.com/q/024h1olDsXfk310000g07U','1492507515');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('5','5','IC channels','http://weixin.qq.com/q/02odz3kysXfk310000w07W','1492507524');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('6','6','All Brands channels','http://weixin.qq.com/q/02xK0Ok5sXfk310000007s','1492507305');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('7','7','Job boards','http://weixin.qq.com/q/02Dr3nk4sXfk310000M079','1492507540');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('8','8','collaborated promotion','http://weixin.qq.com/q/02hs8mlBsXfk310000M07R','1492757860');
//insert into `plugin_weixin_dev_qrcode` (`qrcode_id`, `scene_str`, `desc`, `url`, `createdate`) values('9','9','BJ TC openday','http://weixin.qq.com/q/02IMTtlssXfk310000w07p','1509503299');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_codes');
    }
}
