<?php

namespace App\Filament\Resources\B2bAccounts;

use App\Filament\Resources\B2bAccounts\Pages\CreateB2bAccount;
use App\Filament\Resources\B2bAccounts\Pages\EditB2bAccount;
use App\Filament\Resources\B2bAccounts\Pages\ListB2bAccounts;
use App\Filament\Resources\B2bAccounts\Pages\ViewB2bAccount;
use App\Filament\Resources\B2bAccounts\Schemas\B2bAccountForm;
use App\Filament\Resources\B2bAccounts\Schemas\B2bAccountInfolist;
use App\Filament\Resources\B2bAccounts\Tables\B2bAccountsTable;
use App\Models\B2bAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class B2bAccountResource extends Resource
{
    protected static ?string $model = B2bAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return B2bAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return B2bAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return B2bAccountsTable::configure($table);
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
            'index'  => Pages\ListB2bAccounts::route('/'),
            'create' => Pages\CreateB2bAccount::route('/create'),
            'view'   => Pages\ViewB2bAccount::route('/{record}'),
            'edit'   => Pages\EditB2bAccount::route('/{record}/edit'),
        ];
    }

}
