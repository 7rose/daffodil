<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatPermanentQrcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_permanent_qrcode', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->nullable();
            $table->string('label')->unique();
            $table->string('ticket');
            $table->string('url');
            $table->boolean('avaliable')->default(true);
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
        Schema::table('wechat_permanent_qrcode', function (Blueprint $table) {
             Schema::drop('wechat_permanent_qrcode');
        });
    }
}
