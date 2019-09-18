<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWegistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wegists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('formerName');
            $table->string('form');
            $table->string('crop');
            $table->string('water_level');
            $table->string('water_ph');
            $table->string('water_soil');
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
        Schema::dropIfExists('wegists');
    }
}
