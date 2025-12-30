<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Filament\Resources\Bookings\Schemas\BookingViewSchema;
use App\Models\Booking;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return []; // 🔒 read-only
    }

    protected function resolveRecord(int|string $key): Model
    {
        return Booking::query()
            ->with([
                'form',
                'formVersion',  // Updated relation name
                'vehicle.type',
                'vehicle.company',
                'pricingSnapshot',
                'fieldValues',
                'agreements.agreement',
            ])
            ->findOrFail($key);
    }

}
