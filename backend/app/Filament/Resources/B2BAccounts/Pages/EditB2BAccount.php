<?php

namespace App\Filament\Resources\B2BAccounts\Pages;

use App\Filament\Resources\B2BAccounts\B2BAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditB2BAccount extends EditRecord
{
    protected static string $resource = B2BAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
