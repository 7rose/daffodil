<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardFaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_face', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id');
            $table->integer('ratio')->default(100);
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('limit')->nullable();
            $table->boolean('transferable')->default(true);
            $table->boolean('locked')->default(false);
            $table->integer('created_by');
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
        Schema::table('card_face', function (Blueprint $table) {
            Schema::drop('card_face');
        });
    }
}
