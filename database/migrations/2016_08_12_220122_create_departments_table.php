<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->integer('level');
            $table->string('name')->unique();
            $table->string('name_en')->nullalbe();
            $table->boolean('org')->default(false);
            $table->integer('for_org')->nullalbe();
            $table->boolean('independent')->default(false);
            $table->boolean('allow_admin')->default(true);
            $table->boolean('allow_root')->default(true);
            $table->boolean('allow_master')->default(true);
            $table->boolean('is_shop')->default(false);
            $table->boolean('is_supplier')->default(false);
            $table->boolean('is_customer')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('hide')->default(false);
            $table->integer('order')->nullalbe();
            $table->string('extra')->nullalbe();
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
        Schema::table('departments', function (Blueprint $table) {
            Schema::drop('departments');
        });
    }
}
