<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VehicleType;
use App\Models\Vehicle;

class DemoFleetSeeder extends Seeder
{
    public function run(): void
    {
        $sedan = VehicleType::firstOrCreate(
            ['slug' => 'sedan'],
            ['name' => 'Sedan', 'active' => true]
        );

        Vehicle::firstOrCreate(
            ['name' => 'Mercedes S-Class'],
            [
                'vehicle_type_id' => $sedan->id,
                'vehicle_company_id' => null,
                'make' => 'Mercedes',
                'model' => 'S-Class',
                'passengers' => 3,
                'luggage' => 2,
                'base_price' => 150,
                'price_per_km' => 3.50,
                'price_per_hour' => 80,
                'active' => true,
            ]
        );
    }
}