<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method')->default('offline')->after('updated_at');
            }

            if (!Schema::hasColumn('bookings', 'payment_intent_id')) {
                $table->string('payment_intent_id')->nullable()->after('payment_method');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'payment_intent_id')) {
                $table->dropColumn('payment_intent_id');
            }
            if (Schema::hasColumn('bookings', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
