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
        Schema::create('params', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('city_id', 4)->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->date('start_date')->unique();
            $table->date('end_date')->unique();
            $table->integer('avg_handling_time')->nullable();
            $table->integer('reporting_period')->nullable();
            $table->integer('service_level')->nullable();
            $table->integer('target_answer_time')->nullable();
            $table->integer('shrinkage')->nullable();
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
        Schema::dropIfExists('params');
    }
};
