<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workdays', function (Blueprint $table) {
            $table->id();
            $table->date('employment_date');
            $table->foreignId('time_work_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->date('extra_shift')->nullable();
            $table->date('new_time_work_date')->nullable();
            $table->foreignId('new_time_work_id')->nullable()->constrained('time_works')->nullOnDelete()->cascadeOnUpdate();
            $table->boolean('intern')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workdays');
    }
};
