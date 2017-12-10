<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sn');
            $table->integer('fashion')->nullable();
            $table->decimal('price',8,2);
            $table->integer('type');
            $table->integer('gender');
            $table->string('name')->nullable();
            $table->string('name_en')->nullable();
            $table->string('gold')->nullable();
            $table->string('gold_level')->nullable();
            $table->string('stone')->nullable();
            $table->integer('stone_color')->nullable();
            $table->string('other')->nullable();
            $table->string('img')->nullable();
            $table->boolean('hide')->default(false);
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
        Schema::table('goods', function (Blueprint $table) {
            Schema::drop('goods');
        });
    }
}
