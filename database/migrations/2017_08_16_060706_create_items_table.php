<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('sn');
            $table->integer('type');
            $table->string('title');
            $table->string('summary')->nullable();
            $table->string('img')->nullable();
            $table->decimal('price',8,2);
            $table->integer('unit');
            $table->decimal('num',8,2);
            $table->boolean('for_sale')->default(true);
            $table->boolean('show')->default(true);
            $table->boolean('locked')->default(false);
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
        Schema::table('items', function (Blueprint $table) {
            Schema::drop('items');
        });
    }
}
