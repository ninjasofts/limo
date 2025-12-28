<?php

namespace Database\Seeders;

use App\Models\BookingForm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingFormVersionSeeder extends Seeder
{
    public function run(): void
    {
        BookingForm::with(['fields', 'agreements'])->each(function ($form) {

            // If version 1 already exists for this form, do nothing.
            $exists = DB::table('booking_form_versions')
                ->where('booking_form_id', $form->id)
                ->where('version', 1)
                ->exists();

            if ($exists) {
                return;
            }

            DB::table('booking_form_versions')->insert([
                'booking_form_id' => $form->id,
                'version' => 1,
                'schema' => json_encode([
                    'fields' => $form->fields->toArray(),
                    'agreements' => $form->agreements->toArray(),
                ]),
                'created_at' => now(),
            ]);
        });
    }
}
