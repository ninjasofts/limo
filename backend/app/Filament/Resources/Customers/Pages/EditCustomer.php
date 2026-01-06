<?php

namespace App\Filament\Resources\Customers\CustomerResource\Pages;

use App\Filament\Resources\Customers\CustomerResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;
}
