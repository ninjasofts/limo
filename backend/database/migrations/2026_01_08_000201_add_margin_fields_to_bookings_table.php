<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('provider_payout_total', 10, 2)->default(0)->after('total');
            $table->decimal('margin_amount', 10, 2)->default(0)->after('provider_payout_total');
            $table->decimal('margin_percent', 6, 2)->default(0)->after('margin_amount');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['provider_payout_total', 'margin_amount', 'margin_percent']);
        });
    }
};
