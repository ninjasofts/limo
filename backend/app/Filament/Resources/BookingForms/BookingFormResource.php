<?php

namespace App\Filament\Resources\BookingForms;

use App\Filament\Resources\BookingForms\Pages\CreateBookingForm;
use App\Filament\Resources\BookingForms\Pages\EditBookingForm;
use App\Filament\Resources\BookingForms\Pages\ListBookingForms;
use App\Filament\Resources\BookingForms\Pages\ViewBookingForm;
use App\Filament\Resources\BookingForms\Schemas\BookingFormForm;
use App\Filament\Resources\BookingForms\Schemas\BookingFormInfolist;
use App\Filament\Resources\BookingForms\Tables\BookingFormsTable;
use App\Models\BookingForm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingFormResource extends Resource
{
    protected static ?string $model = BookingForm::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BookingFormForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BookingFormInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingFormsTable::configure($table);
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
            'index' => ListBookingForms::route('/'),
            // 'create' => CreateBookingForm::route('/create'),
            'view' => ViewBookingForm::route('/{record}'),
            // 'edit' => EditBookingForm::route('/{record}/edit'),
        ];
    }
}
