<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('formerName')->nullable();
            $table->integer('farmland')->nullable();
            $table->integer('water_level')->nullable();
            $table->integer('water_ph')->nullable();
            $table->integer('water_soil')->nullable();
            $table->integer('light_lux')->nullable();
            $table->integer('air_cp')->nullable();
            $table->integer('air_ph4')->nullable();
            $table->integer('air_hun')->nullable();
            $table->integer('air_tem')->nullable();
            $table->integer('weather_windSpeed')->nullable();
            $table->integer('weather_windWay')->nullable();
            $table->integer('weather_rainAccumulation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weights');
    }
}
