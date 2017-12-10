<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFashionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fashions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('set')->nullable();
            $table->string('set_en')->nullable();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->integer('type');
            $table->integer('gender')->nullable();
            $table->boolean('locked')->default(false);
            $table->boolean('hide')->default(false);
            $table->string('img')->nullable();
            $table->string('content')->nullable();
            $table->string('content_en')->nullable();
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
        Schema::table('fashions', function (Blueprint $table) {
            Schema::drop('fashions');
        });
    }
}
