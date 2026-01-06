<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\PaymentResource\Pages;
use App\Filament\Resources\Payments\PaymentResource\Tables\PaymentTable;
use App\Models\Payment;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    // protected static ?string $navigationGroup = 'Finance';
    // protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    // protected static ?string $navigationLabel = 'Payments';
    // protected static ?int $navigationSort = 3;

     protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function table(Table $table): Table
    {
        return PaymentTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'view'  => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
