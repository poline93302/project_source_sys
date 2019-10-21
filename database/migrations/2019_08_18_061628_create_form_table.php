<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formerInfo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('farmer')->nullable();
            $table->string('farm')->nullable();
            $table->string('crop')->nullable();
            $table->string('status')->nullable();
            $table->string('farmland')->nullable();
            $table->dateTime('create_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formerInfo');
    }
}
