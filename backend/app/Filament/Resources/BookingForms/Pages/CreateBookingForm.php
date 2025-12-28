<?php

namespace App\Filament\Resources\BookingForms\Pages;

use App\Filament\Resources\BookingForms\BookingFormResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBookingForm extends CreateRecord
{
    protected static string $resource = BookingFormResource::class;
}
