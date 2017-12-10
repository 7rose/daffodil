<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_id')->nullable();
            
            $table->integer('uuid')->nullable();
            $table->string('openid')->unique();

            $table->boolean('subscribe')->nullable();
            $table->string('nickname')->nullable();
            $table->integer('sex')->nullable();
            $table->string('language')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('headimgurl')->nullable();
            $table->integer('subscribe_time')->nullable();
            $table->string('unionid')->nullable();
            $table->string('remark')->nullable();
            $table->string('groupid')->nullable();
            $table->string('tagid_list')->nullable();
            $table->string('subscribe_from')->nullable();
            $table->string('privilege')->nullable();

            $table->string('qrcode_ticket')->nullable();
            $table->string('qrcode_url')->nullable();
            $table->integer('qrcode_seconds')->default(0);
            $table->boolean('new')->default(true);

            $table->string('access_token')->nullable();
            $table->integer('expires_in')->nullable();
            $table->string('refresh_token')->nullable();
            $table->integer('refresh_token_expires_in')->nullable();

            $table->rememberToken();
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
        Schema::table('wechat', function (Blueprint $table) {
            Schema::drop('wechat');
        });
    }
}
