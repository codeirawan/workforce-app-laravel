<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_period', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('forecast_id');
            $table->boolean('publish')->nullable();
            $table->timestamps();

            $table->foreign('forecast_id')->references('id')->on('params')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_period');
    }
};