<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('forecast_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('mon')->default(0);
            $table->integer('tue')->default(0);
            $table->integer('wed')->default(0);
            $table->integer('thu')->default(0);
            $table->integer('fri')->default(0);
            $table->integer('sat')->default(0);
            $table->integer('sun')->default(0);
            $table->integer('sum')->default(0);
            $table->integer('avg')->default(0);
            $table->timestamps();

            $table->foreign('forecast_id')->references('id')->on('params');
            $table->unique(['forecast_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calculations');
    }
};
