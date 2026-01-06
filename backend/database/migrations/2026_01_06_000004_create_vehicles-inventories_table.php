<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('provider_id')->constrained();
            $table->unsignedInteger('quantity'); // e.g. 5 S-Class
            $table->unsignedInteger('buffer')->default(0); // reserved capacity
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['vehicle_id', 'provider_id']);
});

    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_inventories');
    }
};
