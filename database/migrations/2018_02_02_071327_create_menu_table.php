<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->smallInteger('parent_id')->default(0)->comment('父类ID');
            $table->string('name')->default('')->comment('名称');
            $table->string('type')->default('')->comment('类型,如view,button');
            $table->tinyInteger('weight')->default(0)->comment('权重,超小越在前面');
            $table->tinyInteger('is_use')->default(1)->comment('是否在使用');
            $table->string('value')->default('')->comment('事件值');
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
        Schema::dropIfExists('menus');
    }
}
