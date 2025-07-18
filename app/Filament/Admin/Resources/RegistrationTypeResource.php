<?php
// app/Filament/Admin/Resources/RegistrationTypeResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RegistrationTypeResource\Pages;
use App\Models\RegistrationType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;

class RegistrationTypeResource extends Resource
{
    protected static ?string $model = RegistrationType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Business Configuration';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Registration Type Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter registration type name')
                                    ->inlineLabel(),

                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter unique code (e.g., BIR)')
                                    ->unique(ignoreRecord: true)
                                    ->inlineLabel(),
                            ]),

                        Textarea::make('description')
                            ->placeholder('Enter registration type description')
                            ->rows(3)
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('requires_expiry')
                                    ->label('Requires Expiry Date')
                                    ->default(false)
                                    ->inlineLabel(),

                                Toggle::make('requires_document')
                                    ->label('Requires Document Upload')
                                    ->default(true)
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('Enter sort order')
                                    ->inlineLabel(),

                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning'),

                TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('N/A')
                    ->toggleable(),

                IconColumn::make('requires_expiry')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('requires_document')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Registration Types')
                    ->trueLabel('Active Types')
                    ->falseLabel('Inactive Types'),

                TernaryFilter::make('requires_expiry')
                    ->label('Requires Expiry')
                    ->placeholder('All Types')
                    ->trueLabel('With Expiry')
                    ->falseLabel('No Expiry'),

                TernaryFilter::make('requires_document')
                    ->label('Requires Document')
                    ->placeholder('All Types')
                    ->trueLabel('With Document')
                    ->falseLabel('No Document'),
            ])
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrationTypes::route('/'),
            'create' => Pages\CreateRegistrationType::route('/create'),
            'edit' => Pages\EditRegistrationType::route('/{record}/edit'),
        ];
    }
}
