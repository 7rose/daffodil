<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('level');
            $table->integer('org');
            $table->boolean('hide')->default(false);
            $table->boolean('show')->default(true);
            $table->string('name');
            $table->string('name_en')->nullalbe();
            $table->string('content')->nullalbe();
            $table->string('content_en')->nullalbe();
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
        Schema::table('positions', function (Blueprint $table) {
            Schema::drop('positions');
        });
    }
}
