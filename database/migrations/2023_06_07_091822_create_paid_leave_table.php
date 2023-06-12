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
        Schema::create('paid_leave', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('leave_type');
            $table->unsignedBigInteger('by');
            $table->enum('status', ['Draft', 'Submitted', 'Processed', 'Approved', 'Rejected', 'Canceled']);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('leave_type')->references('id')->on('master_leave_types');
            $table->foreign('by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paid_leave');
    }
};
