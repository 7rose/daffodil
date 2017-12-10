<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relation_finance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table');
            $table->integer('table_id');
            $table->boolean('enabled')->default(true);
            $table->string('bank');
            $table->string('bank_en')->nullable();
            $table->string('account');
            $table->string('account_en')->nullable();
            $table->string('account_id');
            $table->boolean('confirmed')->default(false);
            $table->boolean('default')->default(true);
            $table->integer('created_by');
            $table->string('content')->nullable();
            $table->string('content_en')->nullable();
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
        Schema::table('relation_finance', function (Blueprint $table) {
            Schema::drop('relation_finance');
        });
    }
}
