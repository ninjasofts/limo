<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingForm;
use App\Models\BookingFormField;

class BookingFormFieldSeeder extends Seeder
{
    public function run(): void
    {
        $form = BookingForm::where('slug', 'airport-transfers')->firstOrFail();

        BookingFormField::updateOrCreate(
            ['booking_form_id' => $form->id, 'name' => 'flight_number'],
            [
                'label' => 'Flight Number',
                'type' => 'text',
                'required' => true,
                'sort_order' => 1,
            ]
        );

        BookingFormField::updateOrCreate(
            ['booking_form_id' => $form->id, 'name' => 'meet_greet'],
            [
                'label' => 'Meet & Greet',
                'type' => 'checkbox',
                'required' => false,
                'sort_order' => 2,
            ]
        );
    }
}
