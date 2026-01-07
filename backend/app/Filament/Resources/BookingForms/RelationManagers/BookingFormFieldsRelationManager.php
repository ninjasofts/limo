<?php

namespace App\Filament\Resources\BookingForms\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class BookingFormFieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'fields';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('label')
                ->required()
                ->maxLength(255),

            TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->helperText('Machine name used in payload. Example: flight_number'),

            Select::make('type')
                ->required()
                ->options([
                    'text' => 'Text',
                    'email' => 'Email',
                    'number' => 'Number',
                    'textarea' => 'Textarea',
                    'select' => 'Select',
                    'checkbox' => 'Checkbox',
                    'radio' => 'Radio',
                    'date' => 'Date',
                    'time' => 'Time',
                ]),

            Textarea::make('options')
                ->rows(4)
                ->helperText('JSON array of options for select/radio. Example: ["Yes","No"]')
                ->dehydrateStateUsing(function ($state) {
                    if (is_array($state)) return $state;

                    $trim = is_string($state) ? trim($state) : '';
                    if ($trim === '') return null;

                    $decoded = json_decode($trim, true);
                    return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
                })
                ->formatStateUsing(function ($state) {
                    if (is_array($state)) {
                        return json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    }
                    return $state;
                })
                ->visible(fn (callable $get) =>
                    in_array($get('type'), ['select', 'radio'], true)
                ),

            Toggle::make('required')->default(false),

            TextInput::make('sort_order')
                ->numeric()
                ->default(0)
                ->helperText('Lower = earlier in the form.'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('label')->searchable(),
                TextColumn::make('name')->toggleable(),
                TextColumn::make('type')->badge(),
                ToggleColumn::make('required'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
               Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
