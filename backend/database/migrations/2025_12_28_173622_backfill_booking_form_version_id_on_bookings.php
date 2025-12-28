<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('bookings')
            ->join('booking_form_versions', 'bookings.booking_form_id', '=', 'booking_form_versions.booking_form_id')
            ->update([
                'bookings.booking_form_version_id' => DB::raw('booking_form_versions.id'),
            ]);
    }

    public function down(): void
    {
        DB::table('bookings')->update([
            'booking_form_version_id' => null,
        ]);
    }
};
