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
        Schema::create('raw_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date')->unique();
            $table->integer('00_01')->default(0);
            $table->integer('01_02')->default(0);
            $table->integer('02_03')->default(0);
            $table->integer('03_04')->default(0);
            $table->integer('04_05')->default(0);
            $table->integer('05_06')->default(0);
            $table->integer('06_07')->default(0);
            $table->integer('07_08')->default(0);
            $table->integer('08_09')->default(0);
            $table->integer('09_10')->default(0);
            $table->integer('10_11')->default(0);
            $table->integer('11_12')->default(0);
            $table->integer('12_13')->default(0);
            $table->integer('13_14')->default(0);
            $table->integer('14_15')->default(0);
            $table->integer('15_16')->default(0);
            $table->integer('16_17')->default(0);
            $table->integer('17_18')->default(0);
            $table->integer('18_19')->default(0);
            $table->integer('19_20')->default(0);
            $table->integer('20_21')->default(0);
            $table->integer('21_22')->default(0);
            $table->integer('22_23')->default(0);
            $table->integer('23_00')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_data');
    }
};
