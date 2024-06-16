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
        Schema::create('time_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('st1_id')->constrained('metro_stations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('st2_id')->constrained('metro_stations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('time', 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_stations');
    }
};
