<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_logs', function (Blueprint $table) {
						$table->increments('id');
						$table->integer('idSensor')->unsigned()->index();
						$table->boolean('foodEmpty')->nullable();
						$table->boolean('waterEmpty')->nullable();
            $table->timestamps();
						$table->foreign('idSensor')->references('id')->on('sensors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensor_logs');
    }
}
