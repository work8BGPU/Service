<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('employee_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('request_id');
            $table->timestamp('time_start');
            $table->timestamp('time_end');
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_requests');
    }
};
