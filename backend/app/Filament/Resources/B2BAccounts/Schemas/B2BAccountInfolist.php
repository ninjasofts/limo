<?php

namespace App\Filament\Resources\B2BAccounts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class B2BAccountInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('company_name'),
                TextEntry::make('company_type'),
                TextEntry::make('vat_number'),
                TextEntry::make('billing_email'),
                TextEntry::make('discount_type'),
                TextEntry::make('discount_value')
                    ->numeric(),
                TextEntry::make('credit_limit')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
