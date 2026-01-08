<?php

namespace App\Filament\Resources\B2bAccounts\Pages;

use App\Filament\Resources\B2bAccounts\B2bAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditB2bAccount extends EditRecord
{
    protected static string $resource = B2bAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
          ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
