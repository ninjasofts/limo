<?php

namespace App\Filament\Resources\B2BAccounts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class B2BAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_name')
                    ->required(),
                Select::make('company_type')
                    ->options(['corporate' => 'Corporate', 'travel_agency' => 'Travel agency'])
                    ->default('corporate')
                    ->required(),
                TextInput::make('vat_number'),
                TextInput::make('billing_email')
                    ->email(),
                Select::make('discount_type')
                    ->options(['none' => 'None', 'percent' => 'Percent', 'fixed' => 'Fixed'])
                    ->default('none')
                    ->required(),
                TextInput::make('discount_value')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('credit_limit')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('currency')
                    ->required()
                    ->default('DKK'),
                Select::make('status')
                    ->options(['active' => 'Active', 'blocked' => 'Blocked'])
                    ->default('active')
                    ->required(),
            ]);
    }
}
