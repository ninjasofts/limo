<?php

namespace App\Filament\Resources\Customers\CustomerResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class CustomerForm
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Customer Details')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('last_name')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->maxLength(190),

                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(30),

                    Forms\Components\TextInput::make('company')
                        ->maxLength(190),

                    Forms\Components\TextInput::make('country_code')
                        ->label('Country Code')
                        ->maxLength(2),
                ])
                ->columns(2),
        ]);
    }
}
