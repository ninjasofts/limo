<?php

namespace App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class ProviderPayoutForm
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Payout Details')
                ->schema([
                    Forms\Components\Select::make('booking_id')
                        ->relationship('booking', 'id')
                        ->searchable()
                        ->required(),

                    Forms\Components\Select::make('provider_id')
                        ->relationship('provider', 'name')
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('currency')
                        ->default('EUR')
                        ->maxLength(3)
                        ->required(),

                    Forms\Components\TextInput::make('payout_amount')
                        ->numeric()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'paid' => 'Paid',
                            'void' => 'Void',
                        ])
                        ->required(),

                    Forms\Components\Select::make('payout_method')
                        ->options([
                            'bank' => 'Bank',
                            'cash' => 'Cash',
                            'stripe_connect' => 'Stripe Connect',
                            'other' => 'Other',
                        ]),

                    Forms\Components\TextInput::make('reference')
                        ->maxLength(190),

                    Forms\Components\DateTimePicker::make('paid_at')
                        ->visible(fn ($get) => $get('status') === 'paid'),
                ])
                ->columns(2),
        ]);
    }
}
