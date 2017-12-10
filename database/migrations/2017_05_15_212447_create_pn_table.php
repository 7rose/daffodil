<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pn', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pn');
            $table->integer('shop');
            $table->boolean('buy')->default(false);
            $table->boolean('cart')->default(false);
            $table->boolean('closed')->default(false);
            $table->boolean('recieved')->default(false);
            $table->boolean('prepared')->default(false);
            $table->boolean('finished')->default(false);
            $table->boolean('abandoned')->default(false);
            $table->integer('created_by');
            $table->string('content')->nullable();
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
        Schema::table('pn', function (Blueprint $table) {
            Schema::drop('pn');
        });
    }
}
