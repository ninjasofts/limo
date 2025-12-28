<?php

namespace App\Filament\Resources\B2BAccounts\Pages;

use App\Filament\Resources\B2BAccounts\B2BAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListB2BAccounts extends ListRecords
{
    protected static string $resource = B2BAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
