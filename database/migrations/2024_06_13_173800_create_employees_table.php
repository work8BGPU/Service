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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->string('patronymic')->nullable();
            $table->boolean('sex');
            $table->string('number', 8);
            $table->boolean('light_work')->default(false);
            $table->foreignId('work_phone_id')->nullable()->constrained('phones')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('personal_phone_id')->nullable()->constrained('phones')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('position_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('area_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('workday_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
