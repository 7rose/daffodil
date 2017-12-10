<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('owner_wechat_id');
            $table->integer('card_face_id');
            $table->integer('num')->default(1);
            $table->string('from');
            $table->integer('expires_in');
            $table->string('wechat_qrcode_url')->nullable();
            $table->boolean('locked')->default(false);
            $table->string('signature')->nullable();
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
        Schema::table('card', function (Blueprint $table) {
            Schema::drop('card');
        });
    }
}
