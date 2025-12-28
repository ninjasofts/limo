<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillBookingFormVersions extends Command
{
    protected $signature = 'booking:backfill-form-versions';

    protected $description = 'Backfill booking_form_version_id for existing bookings';

    public function handle(): int
    {
        $updated = DB::table('bookings')
            ->join(
                'booking_form_versions',
                'bookings.booking_form_id',
                '=',
                'booking_form_versions.booking_form_id'
            )
            ->whereNull('bookings.booking_form_version_id')
            ->update([
                'bookings.booking_form_version_id' => DB::raw('booking_form_versions.id'),
            ]);

        $this->info("Updated {$updated} bookings.");

        return Command::SUCCESS;
    }
}
