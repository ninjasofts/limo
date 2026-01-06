<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('b2b_account_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('provider', ['stripe','offline'])->default('stripe');
            $table->enum('status', ['pending','succeeded','failed','refunded','cancelled'])->default('pending');

            $table->string('currency', 3)->default('USD');
            $table->decimal('amount', 10, 2)->default(0);

            // Stripe primitives
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->string('stripe_charge_id')->nullable()->index();
            $table->string('stripe_event_id')->nullable()->index(); // webhook idempotency

            $table->timestamp('captured_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['booking_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
