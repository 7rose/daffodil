<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sn')->unique();
            $table->string('mobile')->unique();
            $table->boolean('mobile_confirmed')->default(true);
            $table->integer('org');
            $table->integer('department'); 
            $table->integer('position')->nullable();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->integer('gender')->nullable();
            $table->string('password');
            $table->boolean('new')->default(true);
            $table->boolean('root')->default(false);
            $table->boolean('admin')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('hide')->default(false);
            $table->boolean('visitor')->default(false);
            $table->string('lang')->nullable();
            $table->string('img')->nullable();
            $table->string('content')->nullable();
            $table->string('content_en')->nullable();
            $table->integer('created_by');
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
        Schema::table('staff', function (Blueprint $table) {
            Schema::drop('staff');
        });
    }
}
