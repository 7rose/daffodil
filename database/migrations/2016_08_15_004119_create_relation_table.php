<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table');
            $table->integer('table_id');
            $table->boolean('enabled')->default(true);
            $table->integer('type');
            $table->string('value');
            $table->boolean('confirmed')->default(false);
            $table->boolean('as_id')->default(false);
            $table->boolean('default')->default(false);
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
        Schema::table('relations', function (Blueprint $table) {
            Schema::drop('relations');
        });
    }
}
