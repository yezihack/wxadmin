<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQrStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qr_id')->default(0)->comment('qrCode表id相关联');
            $table->string('openid', 30)->default(0)->comment('openid');
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
        Schema::dropIfExists('qr_stats');
    }
}
