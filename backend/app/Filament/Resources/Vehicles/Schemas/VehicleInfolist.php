<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('vehicle_type_id')
                    ->numeric(),
                TextEntry::make('vehicle_company_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('make'),
                TextEntry::make('model'),
                TextEntry::make('passengers')
                    ->numeric(),
                TextEntry::make('luggage')
                    ->numeric(),
                TextEntry::make('base_price')
                    ->numeric(),
                TextEntry::make('price_per_km')
                    ->numeric(),
                TextEntry::make('price_per_hour')
                    ->numeric(),
                IconEntry::make('active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
