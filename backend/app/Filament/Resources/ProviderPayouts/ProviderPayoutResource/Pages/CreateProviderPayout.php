<?php

namespace App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Pages;

use App\Filament\Resources\ProviderPayouts\ProviderPayoutResource;
use App\Services\Margins\BookingMarginService;
use Filament\Resources\Pages\CreateRecord;

class CreateProviderPayout extends CreateRecord
{
    protected static string $resource = ProviderPayoutResource::class;

    protected function afterCreate(): void
    {
        $svc = app(BookingMarginService::class);
        $svc->recompute($this->record->booking);
    }
}
