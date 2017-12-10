<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGridTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grid', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->boolean('enabled')->default(true);
            $table->boolean('with_or_except')->default(true);
            $table->boolean('root')->default(true);
            $table->boolean('admin')->default(true);
            $table->boolean('owner')->default(false);
            $table->boolean('app')->default(true);
            $table->boolean('wechat')->default(true);
            $table->boolean('web')->default(true);
            $table->boolean('mobile')->default(true);
            $table->boolean('pad')->default(true);
            $table->boolean('monitor')->default(false);
            $table->boolean('teminal')->default(true);
            $table->string('department')->nullable();
            $table->string('position')->nullable();
            $table->string('user')->nullable();
            $table->integer('created_by');
            $table->string('content')->nullable();
            $table->string('content_en')->nullable();
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
        Schema::table('grid', function (Blueprint $table) {
            Schema::drop('grid');
        });
    }
}
