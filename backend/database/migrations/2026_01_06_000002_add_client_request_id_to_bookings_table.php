<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'client_request_id')) {
                $table->uuid('client_request_id')->nullable()->unique()->after('booking_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'client_request_id')) {
                $table->dropUnique(['client_request_id']);
                $table->dropColumn('client_request_id');
            }
        });
    }
};
