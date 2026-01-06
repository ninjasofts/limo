<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();

            $table->string('company')->nullable();
            $table->string('country_code', 2)->nullable();

            $table->timestamp('last_seen_at')->nullable();
            $table->unsignedInteger('bookings_count')->default(0);

            $table->timestamps();

            // soft uniqueness without forcing email for all cases
            $table->index(['email', 'phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
