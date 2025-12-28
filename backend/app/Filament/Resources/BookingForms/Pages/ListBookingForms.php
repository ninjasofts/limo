<?php

namespace App\Filament\Resources\BookingForms\Pages;

use App\Filament\Resources\BookingForms\BookingFormResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingForms extends ListRecords
{
    protected static string $resource = BookingFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
