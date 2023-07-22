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
        Schema::create('shift_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('shift_id');
            $table->timestamps();

            $table->foreign('skill_id')->references('id')->on('master_skills')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('master_shift')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_skills');
    }
};