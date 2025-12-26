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
        Schema::create('vehicles', function (Blueprint $table) {
    $table->id();

    $table->foreignId('vehicle_type_id')->constrained()->cascadeOnDelete();
    $table->foreignId('vehicle_company_id')->nullable()->constrained()->nullOnDelete();

    $table->string('name');
    $table->string('make')->nullable();
    $table->string('model')->nullable();

    $table->unsignedInteger('passengers')->default(1);
    $table->unsignedInteger('luggage')->default(0);

    $table->decimal('base_price', 10, 2)->default(0);
    $table->decimal('price_per_km', 10, 2)->default(0);
    $table->decimal('price_per_hour', 10, 2)->default(0);

    $table->boolean('active')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
