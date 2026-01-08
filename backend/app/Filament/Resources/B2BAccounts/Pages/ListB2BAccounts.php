<?php

namespace App\Filament\Resources\B2bAccounts\Pages;

use App\Filament\Resources\B2bAccounts\B2bAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListB2bAccounts extends ListRecords
{
    protected static string $resource = B2bAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
