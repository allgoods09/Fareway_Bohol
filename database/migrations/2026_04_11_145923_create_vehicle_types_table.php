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
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tricycle, Motorcycle, Multi-cab, Bus
            $table->string('icon')->default('fa-solid fa-bus');
            $table->decimal('base_fare', 10, 2);
            $table->decimal('base_km', 10, 2);
            $table->decimal('per_km_rate', 10, 2);
            $table->decimal('night_surcharge', 10, 2)->default(0); // fixed surcharge
            $table->time('night_start')->default('20:00:00');
            $table->time('night_end')->default('05:00:00');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
