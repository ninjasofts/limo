<?php

namespace App\Filament\Resources\Payments\PaymentResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class PaymentTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking.id')
                    ->label('Booking ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'succeeded' => 'success',
                        'failed'    => 'danger',
                        'refunded'  => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('stripe_payment_intent_id')
                    ->label('Stripe PI')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since(),
            ])
            ->actions([
               ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
