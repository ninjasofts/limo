<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_agreements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('booking_form_agreement_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('accepted')->default(false);

            $table->timestamps();

            // One booking should not store the same agreement twice
            $table->unique(['booking_id', 'booking_form_agreement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_agreements');
    }
};
