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
        Schema::create('bookings', function (Blueprint $table) {
    $table->id();

    $table->string('booking_number')->unique();

    $table->foreignId('booking_form_id')->constrained()->cascadeOnDelete();
    $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();

    // later: link to b2b accounts
    $table->foreignId('b2b_account_id')->nullable()->constrained()->nullOnDelete();

    $table->enum('service_type', ['distance', 'hourly', 'flat']);
    $table->enum('transfer_type', ['one_way', 'return', 'return_new_ride'])->default('one_way');

    $table->timestamp('pickup_at');
    $table->timestamp('return_at')->nullable();

    $table->string('pickup_address');
    $table->decimal('pickup_lat', 10, 7)->nullable();
    $table->decimal('pickup_lng', 10, 7)->nullable();

    $table->string('dropoff_address')->nullable();
    $table->decimal('dropoff_lat', 10, 7)->nullable();
    $table->decimal('dropoff_lng', 10, 7)->nullable();

    $table->json('waypoints')->nullable(); // array of {address, lat, lng}

    $table->decimal('distance_km', 10, 2)->nullable();
    $table->integer('duration_min')->nullable();
    $table->integer('extra_time_min')->default(0);

    $table->unsignedInteger('adults')->default(1);
    $table->unsignedInteger('children')->default(0);
    $table->unsignedInteger('luggage')->default(0);

    // pricing snapshot (calculated later)
    $table->string('currency', 3)->default('USD');
    $table->decimal('subtotal', 10, 2)->default(0);
    $table->decimal('tax', 10, 2)->default(0);
    $table->decimal('discount', 10, 2)->default(0);
    $table->decimal('total', 10, 2)->default(0);

    $table->enum('status', ['pending','processing','cancelled','completed','on_hold','refunded','failed'])
        ->default('pending');

    $table->enum('payment_status', ['unpaid','partial','paid','refunded','failed'])
        ->default('unpaid');

    // customer snapshot
    $table->string('customer_first_name')->nullable();
    $table->string('customer_last_name')->nullable();
    $table->string('customer_email')->nullable();
    $table->string('customer_phone')->nullable();
    $table->text('customer_note')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
