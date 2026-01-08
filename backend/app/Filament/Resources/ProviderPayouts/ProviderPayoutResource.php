<?php

namespace App\Filament\Resources\ProviderPayouts;

use App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Pages;
use App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Schemas\ProviderPayoutForm;
use App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Tables\ProviderPayoutTable;
use App\Models\ProviderPayout;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use BackedEnum;

class ProviderPayoutResource extends Resource
{
    protected static ?string $model = ProviderPayout::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProviderPayoutForm::make($schema);
    }

    public static function table($table): Table
    {
        return ProviderPayoutTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProviderPayouts::route('/'),
            'create' => Pages\CreateProviderPayout::route('/create'),
            'edit'   => Pages\EditProviderPayout::route('/{record}/edit'),
            'view'   => Pages\ViewProviderPayout::route('/{record}'),
        ];
    }
}
