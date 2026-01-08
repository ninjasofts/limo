<?php

namespace App\Filament\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking_number')
                    ->searchable(),
                TextColumn::make('booking_form_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('vehicle_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('b2b_account_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('service_type'),
                TextColumn::make('transfer_type'),
                TextColumn::make('pickup_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('return_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('pickup_address')
                    ->searchable(),
                TextColumn::make('pickup_lat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pickup_lng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dropoff_address')
                    ->searchable(),
                TextColumn::make('dropoff_lat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('dropoff_lng')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('distance_km')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('duration_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('extra_time_min')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('adults')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('children')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('luggage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('subtotal')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tax')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('discount')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status'),
                TextColumn::make('payment_status'),
                TextColumn::make('customer_first_name')
                    ->searchable(),
                TextColumn::make('customer_last_name')
                    ->searchable(),
                TextColumn::make('customer_email')
                    ->searchable(),
                TextColumn::make('customer_phone')
                    ->searchable(),
                TextColumn::make('provider_payout_total')->numeric()->sortable(),
                TextColumn::make('margin_amount')->numeric()->sortable(),
                TextColumn::make('margin_percent')->suffix('%')->numeric()->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\ViewAction::make(),
            ])
            ->toolbarActions([
                // No destructive bulk actions in read-first phase.
            ]);
    }
}
