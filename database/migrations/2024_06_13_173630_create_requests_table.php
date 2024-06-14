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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passenger_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('station_departure_id')->nullable()->constrained('metro_stations')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('station_arrival_id')->nullable()->constrained('metro_stations')->nullOnDelete()->cascadeOnUpdate();
            $table->text('description_departure')->nullable();
            $table->text('description_arrival')->nullable();
            $table->dateTime('date_meet');
            $table->time('time_start')->nullable();
            $table->time('time_end')->nullable();
            $table->time('time_over')->nullable();
            $table->foreignId('method_id')->nullable()->constrained('request_methods')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('number_passenger')->default(1);
            $table->foreignId('category_id')->nullable()->constrained('category_passengers')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedTinyInteger('insp_m')->default(0);
            $table->unsignedTinyInteger('insp_f')->default(0);
            $table->foreignId('luggage_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('status_id')->nullable()->constrained('request_statuses')->nullOnDelete()->cascadeOnUpdate();
            $table->text('information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
