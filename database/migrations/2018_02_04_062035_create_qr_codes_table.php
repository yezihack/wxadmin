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
