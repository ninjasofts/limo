<?php

namespace App\Filament\Resources\Customers\CustomerResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;

class CustomerTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn ($record) => $record->full_name ?: '—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Bookings')
                    ->sortable(),

                Tables\Columns\TextColumn::make('last_seen_at')
                    ->dateTime()
                    ->since()
                    ->label('Last Seen'),
            ])
            ->actions([
               ViewAction::make(),
               EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
