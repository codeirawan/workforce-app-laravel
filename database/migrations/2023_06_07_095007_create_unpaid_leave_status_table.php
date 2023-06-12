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
        Schema::create('unpaid_leave_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('leave_id')->index();
            $table->enum('status', ['Draft', 'Submitted', 'Processed', 'Approved', 'Rejected', 'Canceled']);
            $table->dateTime('at');
            $table->unsignedBigInteger('by');
            $table->string('note')->nullable();

            $table->foreign('leave_id')->references('id')->on('unpaid_leave');
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
        Schema::dropIfExists('unpaid_leave_status');
    }
};
