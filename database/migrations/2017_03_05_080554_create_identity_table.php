<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table'); //table
            $table->integer('client_id'); //user id
            $table->integer('department')->nullable(); 
            $table->integer('position')->nullable();

            $table->integer('stars')->nullable(); //level of people

            $table->boolean('root')->default(false); //root user
            $table->boolean('admin')->default(false); //administrator

            $table->boolean('staff')->default(false);
            $table->boolean('customer')->default(false);
            $table->boolean('supplier')->default(false);
            $table->boolean('agent')->default(false);
            $table->boolean('sailer')->default(false);

            $table->boolean('wechat_user')->default(true);
            $table->boolean('monitor')->default(false);
            $table->boolean('teminal')->default(false);
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
        Schema::drop('identites');
    }
}
