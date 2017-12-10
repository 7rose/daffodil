<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_sn');
            $table->integer('order_pn')->nullable();
            $table->integer('order_buy_pn')->nullable();
            $table->integer('sn');
            $table->integer('shop')->nullable();
            $table->decimal('weight',6,3)->nullable();
            $table->decimal('gold_weight',6,3)->nullable();
            $table->decimal('stone_weight',6,3)->nullable();
            $table->string('ca')->nullable();
            $table->string('other')->nullable();
            $table->string('content')->nullable();
            $table->string('content_en')->nullable();
            $table->boolean('hide')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('for_sale')->default(false);
            $table->boolean('in_service')->default(false);
            $table->boolean('need_label')->default(false);
            $table->boolean('sold')->default(false);
            $table->decimal('sold_price',8,2)->nullable();
            $table->integer('sold_by')->nullable();
            $table->timestamp('sold_time')->nullable();
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
        Schema::table('offers', function (Blueprint $table) {
            Schema::drop('offers');
        });
    }
}
