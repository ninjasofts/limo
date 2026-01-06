<?php

namespace App\Filament\Resources\B2BAccounts;

use App\Filament\Resources\B2BAccounts\Pages\CreateB2BAccount;
use App\Filament\Resources\B2BAccounts\Pages\EditB2BAccount;
use App\Filament\Resources\B2BAccounts\Pages\ListB2BAccounts;
use App\Filament\Resources\B2BAccounts\Pages\ViewB2BAccount;
use App\Filament\Resources\B2BAccounts\Schemas\B2BAccountForm;
use App\Filament\Resources\B2BAccounts\Schemas\B2BAccountInfolist;
use App\Filament\Resources\B2BAccounts\Tables\B2BAccountsTable;
use App\Models\B2BAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class B2BAccountResource extends Resource
{
    protected static ?string $model = B2BAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return B2BAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return B2BAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return B2BAccountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListB2BAccounts::route('/'),
            'create' => Pages\CreateB2BAccount::route('/create'),
            'view'   => Pages\ViewB2BAccount::route('/{record}'),
            'edit'   => Pages\EditB2BAccount::route('/{record}/edit'),
        ];
    }

}
