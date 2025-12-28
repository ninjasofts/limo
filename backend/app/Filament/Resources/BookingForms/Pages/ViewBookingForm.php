<?php

namespace App\Filament\Resources\BookingForms\Pages;

use App\Filament\Resources\BookingForms\BookingFormResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingForm extends ViewRecord
{
    protected static string $resource = BookingFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
