<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_payouts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();

            $table->string('currency', 3)->default('USD');

            // Payout accounting
            $table->decimal('payout_amount', 10, 2)->default(0); // what we owe provider
            $table->enum('status', ['pending', 'approved', 'paid', 'void'])->default('pending');

            // Optional future: payout method tracking
            $table->enum('payout_method', ['bank', 'cash', 'stripe_connect', 'other'])->nullable();
            $table->string('reference')->nullable(); // txn ref, bank ref, etc.
            $table->timestamp('paid_at')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->unique(['booking_id', 'provider_id']); // prevents duplicates for same booking/provider
            $table->index(['provider_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_payouts');
    }
};
