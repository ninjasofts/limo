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
        Schema::create('booking_form_versions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('booking_form_id')->constrained()->cascadeOnDelete();

    $table->unsignedInteger('version'); // 1, 2, 3...
    $table->json('schema');             // fields + agreements snapshot
    $table->timestamp('created_at');

    $table->unique(['booking_form_id', 'version']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_form_versions');
    }
};
