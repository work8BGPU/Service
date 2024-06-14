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
        Schema::create('jobless_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')->constrained('jobless_statuses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('workday_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('start');
            $table->date('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobless_days');
    }
};
