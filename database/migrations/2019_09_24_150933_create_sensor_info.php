<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('former');
            $table->string('farmland');
            $table->string('sensor');
            $table->integer('max');
            $table->integer('min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_info');
    }
}
