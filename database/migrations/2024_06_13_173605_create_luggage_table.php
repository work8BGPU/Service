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
        Schema::create('luggage', function (Blueprint $table) {
            $table->id();
            $table->float('weight', 2);
            $table->foreignId('luggage_type_id')->nullable()->constrained()->nullOnDelete()->cascadeOnUpdate();
            $table->boolean('need_help')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luggage');
    }
};
