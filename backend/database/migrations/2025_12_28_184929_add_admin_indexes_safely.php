<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        $rows = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);
        return count($rows) > 0;
    }

    public function up(): void
    {
        // bookings.created_at
        if (! $this->indexExists('bookings', 'bookings_created_at_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('created_at');
            });
        }

        // bookings.booking_form_id
        if (! $this->indexExists('bookings', 'bookings_booking_form_id_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('booking_form_id');
            });
        }

        // booking_pricing_snapshots.booking_id
        if (! $this->indexExists('booking_pricing_snapshots', 'booking_pricing_snapshots_booking_id_index')) {
            Schema::table('booking_pricing_snapshots', function (Blueprint $table) {
                $table->index('booking_id');
            });
        }

        // (Optional but recommended) booking_field_values.booking_id
        if (Schema::hasTable('booking_field_values') && ! $this->indexExists('booking_field_values', 'booking_field_values_booking_id_index')) {
            Schema::table('booking_field_values', function (Blueprint $table) {
                $table->index('booking_id');
            });
        }

        // (Optional but recommended) booking_agreement_acceptances.booking_id
        if (Schema::hasTable('booking_agreement_acceptances') && ! $this->indexExists('booking_agreement_acceptances', 'booking_agreement_acceptances_booking_id_index')) {
            Schema::table('booking_agreement_acceptances', function (Blueprint $table) {
                $table->index('booking_id');
            });
        }
    }

    public function down(): void
    {
        // Down() is optional here in dev; keeping it safe.
        // If you want, we can add dropIndex() calls later with checks.
    }
};
