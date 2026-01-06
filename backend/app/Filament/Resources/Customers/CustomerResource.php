<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\CustomerResource\Pages;
use App\Filament\Resources\Customers\CustomerResource\Pages\ListCustomers;
use App\Filament\Resources\Customers\CustomerResource\Pages\CreateCustomer;
use App\Filament\Resources\Customers\CustomerResource\Pages\ViewCustomer;
use App\Filament\Resources\Customers\CustomerResource\Pages\EditCustomer;
use App\Filament\Resources\Customers\CustomerResource\Schemas\CustomerForm;
use App\Filament\Resources\Customers\CustomerResource\Tables\CustomerTable;
use App\Models\Customer;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CustomerForm::make($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit'   => Pages\EditCustomer::route('/{record}/edit'),
            'view'   => Pages\ViewCustomer::route('/{record}'),
        ];
    }
}
