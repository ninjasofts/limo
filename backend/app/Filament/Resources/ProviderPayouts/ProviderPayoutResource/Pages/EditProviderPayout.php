<?php

namespace App\Filament\Resources\ProviderPayouts\ProviderPayoutResource\Pages;

use App\Filament\Resources\ProviderPayouts\ProviderPayoutResource;
use App\Services\Margins\BookingMarginService;
use Filament\Resources\Pages\EditRecord;

class EditProviderPayout extends EditRecord
{
    protected static string $resource = ProviderPayoutResource::class;

    protected function afterSave(): void
    {
        $svc = app(BookingMarginService::class);
        $svc->recompute($this->record->booking);
    }
}
