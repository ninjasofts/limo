<?php

namespace App\Filament\Resources\BookingForms\Pages;

use App\Filament\Resources\BookingForms\BookingFormResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingForm extends EditRecord
{
    protected static string $resource = BookingFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
          ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
