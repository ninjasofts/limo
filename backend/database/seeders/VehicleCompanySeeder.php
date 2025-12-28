<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleCompany;

class VehicleCompanySeeder extends Seeder
{
    public function run(): void
    {
        VehicleCompany::updateOrCreate(
            ['name' => 'Royalway Fleet'],
            ['email' => 'fleet@royalway.dk', 'active' => true]
        );
    }
}
