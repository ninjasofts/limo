<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingForm;
use App\Models\BookingFormAgreement;

class BookingFormAgreementSeeder extends Seeder
{
    public function run(): void
    {
        $form = BookingForm::where('slug', 'airport-transfers')->firstOrFail();

        BookingFormAgreement::updateOrCreate(
    ['booking_form_id' => $form->id, 'label' => 'Terms & Conditions'],
    [
        'content' => 'I agree to the booking terms and conditions.',
        'required' => true,
    ]
    );
    }
}
