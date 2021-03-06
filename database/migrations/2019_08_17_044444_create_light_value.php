<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLightValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lightRecy', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('former');
            $table->integer('farmland');
            $table->string('sensor');
            $table->double('value');
            $table->dateTime('send_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lightRecy');
    }
}
