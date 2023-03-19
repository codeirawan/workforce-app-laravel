<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_cities', function (Blueprint $table) {
            $table->char('id', 4);
            $table->char('province_id', 2);
            $table->string('name', 255);

            $table->primary('id');
            $table->foreign('province_id')->references('id')->on('master_provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_cities');
    }
}
