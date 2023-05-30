<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nik')->index();
            $table->string('name');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender', ['Male', 'Female']);
            $table->enum('religion', ['Islam', 'Christian', 'Catholic', 'Hinduism', 'Buddhism', 'Confucianism', 'Other']);
            $table->char('city_id', 4)->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->unsignedBigInteger('skill_id')->index();
            $table->boolean('active')->default(1);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
