<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('vehicle_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('vehicle_company_id')
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('make'),
                TextInput::make('model'),
                TextInput::make('passengers')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('luggage')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('base_price')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('price_per_km')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('price_per_hour')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
