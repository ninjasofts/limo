<?php

namespace Database\Seeders;
use App\Models\VehicleType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    public function run(): void
    {
        VehicleType::updateOrCreate(
            ['slug' => 'sedan'],
            ['name' => 'Sedan', 'active' => true]
        );
    }
}