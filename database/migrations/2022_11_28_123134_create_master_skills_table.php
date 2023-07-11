<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('city_id', 4)->nullable()->index();
            $table->unsignedBigInteger('project_id')->nullable()->index();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')->references('id')->on('master_cities');
            $table->foreign('project_id')->references('id')->on('master_projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_skills');
    }
}
