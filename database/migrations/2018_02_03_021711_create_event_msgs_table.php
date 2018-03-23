<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventMsgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_msgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->comment('消息名称');
            $table->char('event_code', 50)->default(0)->comment('事件类型代码');
            $table->unsignedInteger('parent_id')->default(0)->comment('父ID关联');
            $table->string('title')->default('')->comment('消息标题');
            $table->string('pic_url')->default('')->comment('图片地址');
            $table->text('desc')->comment('消息描述');
            $table->string('url')->default('')->comment('消息链接');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_msgs');
    }
}
