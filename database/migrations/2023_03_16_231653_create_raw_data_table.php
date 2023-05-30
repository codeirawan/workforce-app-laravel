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
            $table->date('date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('volume')->default(0);
            $table->char('city_id', 4)->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->string('batch_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')->references('id')->on('master_cities');
            $table->foreign('project_id')->references('id')->on('master_projects');
            $table->foreign('skill_id')->references('id')->on('master_skills');

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
