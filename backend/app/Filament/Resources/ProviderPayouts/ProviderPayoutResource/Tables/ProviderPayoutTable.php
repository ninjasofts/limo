<?php

namespace App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Table;

class ProviderPayoutTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),

                Tables\Columns\TextColumn::make('booking.id')
                    ->label('Booking')
                    ->sortable(),

                Tables\Columns\TextColumn::make('provider.name')
                    ->label('Provider')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('payout_amount')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->since()
                    ->toggleable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
