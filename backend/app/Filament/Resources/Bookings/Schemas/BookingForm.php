<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_number')
                    ->required(),
                TextInput::make('booking_form_id')
                    ->required()
                    ->numeric(),
                TextInput::make('vehicle_id')
                    ->numeric(),
                TextInput::make('b2b_account_id')
                    ->numeric(),
                Select::make('service_type')
                    ->options(['distance' => 'Distance', 'hourly' => 'Hourly', 'flat' => 'Flat'])
                    ->required(),
                Select::make('transfer_type')
                    ->options(['one_way' => 'One way', 'return' => 'Return', 'return_new_ride' => 'Return new ride'])
                    ->default('one_way')
                    ->required(),
                DateTimePicker::make('pickup_at')
                    ->required(),
                DateTimePicker::make('return_at'),
                TextInput::make('pickup_address')
                    ->required(),
                TextInput::make('pickup_lat')
                    ->numeric(),
                TextInput::make('pickup_lng')
                    ->numeric(),
                TextInput::make('dropoff_address'),
                TextInput::make('dropoff_lat')
                    ->numeric(),
                TextInput::make('dropoff_lng')
                    ->numeric(),
                TextInput::make('waypoints'),
                TextInput::make('distance_km')
                    ->numeric(),
                TextInput::make('duration_min')
                    ->numeric(),
                TextInput::make('extra_time_min')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('adults')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('children')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('luggage')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('discount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'processing' => 'Processing',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            'on_hold' => 'On hold',
            'refunded' => 'Refunded',
            'failed' => 'Failed',
        ])
                    ->default('pending')
                    ->required(),
                Select::make('payment_status')
                    ->options([
            'unpaid' => 'Unpaid',
            'partial' => 'Partial',
            'paid' => 'Paid',
            'refunded' => 'Refunded',
            'failed' => 'Failed',
        ])
                    ->default('unpaid')
                    ->required(),
                TextInput::make('customer_first_name'),
                TextInput::make('customer_last_name'),
                TextInput::make('customer_email')
                    ->email(),
                TextInput::make('customer_phone')
                    ->tel(),
                Textarea::make('customer_note')
                    ->columnSpanFull(),
            ]);
    }
}
