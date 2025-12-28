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
        Schema::create('booking_agreement_acceptances', function (Blueprint $table) {
    $table->id();

    $table->foreignId('booking_id')
        ->constrained()
        ->cascadeOnDelete();

    $table->foreignId('booking_form_agreement_id')
        ->constrained('booking_form_agreements')
        ->cascadeOnDelete();

    $table->boolean('accepted')->default(true);

    $table->timestamps(); // ✅ REQUIRED
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_agreement_acceptances');
    }
};
