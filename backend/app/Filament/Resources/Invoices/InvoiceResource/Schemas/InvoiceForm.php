<?php

namespace App\Filament\Resources\Invoices\InvoiceResource\Schemas;

use Filament\Forms;
use Filament\Forms\Form;

class InvoiceForm
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Invoice')
                ->schema([
                    Forms\Components\TextInput::make('invoice_number')
                        ->disabled(),

                    Forms\Components\TextInput::make('status')
                        ->disabled(),

                    Forms\Components\TextInput::make('currency')
                        ->disabled(),

                    Forms\Components\TextInput::make('subtotal')
                        ->disabled(),

                    Forms\Components\TextInput::make('tax')
                        ->disabled(),

                    Forms\Components\TextInput::make('discount')
                        ->disabled(),

                    Forms\Components\TextInput::make('total')
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('issued_at')
                        ->disabled(),

                    Forms\Components\DateTimePicker::make('due_at')
                        ->disabled(),
                ])
                ->columns(2),
        ]);
    }
}
