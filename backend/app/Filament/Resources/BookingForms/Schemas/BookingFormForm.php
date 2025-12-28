<?php

namespace App\Filament\Resources\BookingForms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BookingFormForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('services')
                    ->required(),
                TextInput::make('settings'),
                Toggle::make('active')
                    ->required(),
            ]);
    }
}
