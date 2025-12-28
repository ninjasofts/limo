<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('booking_number'),
                TextEntry::make('booking_form_id')
                    ->numeric(),
                TextEntry::make('vehicle_id')
                    ->numeric(),
                TextEntry::make('b2b_account_id')
                    ->numeric(),
                TextEntry::make('service_type'),
                TextEntry::make('transfer_type'),
                TextEntry::make('pickup_at')
                    ->dateTime(),
                TextEntry::make('return_at')
                    ->dateTime(),
                TextEntry::make('pickup_address'),
                TextEntry::make('pickup_lat')
                    ->numeric(),
                TextEntry::make('pickup_lng')
                    ->numeric(),
                TextEntry::make('dropoff_address'),
                TextEntry::make('dropoff_lat')
                    ->numeric(),
                TextEntry::make('dropoff_lng')
                    ->numeric(),
                TextEntry::make('distance_km')
                    ->numeric(),
                TextEntry::make('duration_min')
                    ->numeric(),
                TextEntry::make('extra_time_min')
                    ->numeric(),
                TextEntry::make('adults')
                    ->numeric(),
                TextEntry::make('children')
                    ->numeric(),
                TextEntry::make('luggage')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('tax')
                    ->numeric(),
                TextEntry::make('discount')
                    ->numeric(),
                TextEntry::make('total')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('payment_status'),
                TextEntry::make('customer_first_name'),
                TextEntry::make('customer_last_name'),
                TextEntry::make('customer_email'),
                TextEntry::make('customer_phone'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
