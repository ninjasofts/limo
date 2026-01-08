<?php

namespace App\Filament\Resources\B2bAccounts\Pages;

use App\Filament\Resources\B2bAccounts\B2bAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewB2bAccount extends ViewRecord
{
    protected static string $resource = B2bAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
          EditAction::make(),
        ];
    }
}
