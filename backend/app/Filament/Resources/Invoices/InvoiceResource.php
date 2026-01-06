<?php

namespace App\Filament\Resources\Invoices;

use App\Filament\Resources\Invoices\InvoiceResource\Pages;
use App\Filament\Resources\Invoices\InvoiceResource\Schemas\InvoiceForm;
use App\Filament\Resources\Invoices\InvoiceResource\Tables\InvoiceTable;
use App\Models\Invoice;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use BackedEnum;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InvoiceForm::make($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoiceTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'view'  => Pages\ViewInvoice::route('/{record}'),
        ];
    }
}
