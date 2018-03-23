<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('msg_id')->comment('消息ID,与event_msgs表关联');
            $table->char('type', 50)->comment('微信操作类型,类型定在于events');
            $table->string('content')->default('')->comment('如关键字');
            $table->tinyInteger('is_use')->default(0)->comment('是否使用中,1使用，0停用');
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
        Schema::dropIfExists('events');
    }
}
