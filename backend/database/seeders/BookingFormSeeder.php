<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BookingForm;

class BookingFormSeeder extends Seeder
{
    public function run(): void
    {
        BookingForm::updateOrCreate(
            ['slug' => 'airport-transfers'],
            [
                'name' => 'Airport Transfers',
                'currency' => 'EUR',
                'services' => ['distance', 'hourly'],
                'settings' => [
                    'allow_waypoints' => true,
                    'require_customer_account' => false
                ],
                'active' => true,
            ]
        );
    }
}
