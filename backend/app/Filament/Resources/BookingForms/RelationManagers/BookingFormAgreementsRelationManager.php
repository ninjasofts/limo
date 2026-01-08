<?php

namespace App\Filament\Resources\BookingForms\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class BookingFormAgreementsRelationManager extends RelationManager
{
    protected static string $relationship = 'agreements';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('label')
                ->required()
                ->maxLength(255),

            RichEditor::make('content')
                ->required()
                ->columnSpanFull()
                ->helperText('Displayed to the customer and stored in the version snapshot.'),

            Toggle::make('required')
                ->default(true)
                ->helperText('If required, booking cannot be submitted unless accepted.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->searchable(),
                ToggleColumn::make('required'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
              EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}