<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\VehicleCompany;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $type = VehicleType::firstOrFail();
        $company = VehicleCompany::firstOrFail();

        Vehicle::updateOrCreate(
            ['name' => 'Mercedes S-Class'],
            [
                'vehicle_type_id' => $type->id,
                'vehicle_company_id' => $company->id,
                'passengers' => 3,
                'luggage' => 2,
                'base_price' => 150,
                'price_per_km' => 3.5,
                'price_per_hour' => 80,
                'active' => true,
            ]
        );
    }
}
