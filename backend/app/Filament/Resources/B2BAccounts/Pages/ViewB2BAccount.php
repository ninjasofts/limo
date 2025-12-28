<?php

namespace App\Filament\Resources\B2BAccounts\Pages;

use App\Filament\Resources\B2BAccounts\B2BAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewB2BAccount extends ViewRecord
{
    protected static string $resource = B2BAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
