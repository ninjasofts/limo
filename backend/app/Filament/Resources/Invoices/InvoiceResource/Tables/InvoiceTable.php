<?php

namespace App\Filament\Resources\Invoices\InvoiceResource\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class InvoiceTable
{
    public static function make(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('booking.id')
                    ->label('Booking'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'paid' => 'success',
                        'issued' => 'warning',
                        'overdue' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_at')
                    ->date()
                    ->label('Due'),

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
