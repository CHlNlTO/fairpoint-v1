<?php
// app/Filament/Admin/Resources/IncomeTaxTypeResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IncomeTaxTypeResource\Pages;
use App\Models\IncomeTaxType;
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
use Filament\Tables\Filters\TernaryFilter;

class IncomeTaxTypeResource extends Resource
{
    protected static ?string $model = IncomeTaxType::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Tax Configuration';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Income Tax Type Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter income tax type name')
                                    ->inlineLabel(),

                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter unique code (e.g., REGULAR)')
                                    ->unique(ignoreRecord: true)
                                    ->inlineLabel(),
                            ]),

                        Textarea::make('description')
                            ->placeholder('Enter income tax type description')
                            ->rows(3)
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('rate')
                                    ->numeric()
                                    ->suffix('%')
                                    ->placeholder('Enter tax rate (e.g., 30)')
                                    ->step(0.01)
                                    ->inlineLabel(),

                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->placeholder('Enter sort order')
                                    ->inlineLabel(),
                            ]),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inlineLabel(),
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
                    ->color('danger'),

                TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('rate')
                    ->suffix('%')
                    ->sortable()
                    ->placeholder('N/A'),

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
                    ->placeholder('All Tax Types')
                    ->trueLabel('Active Tax Types')
                    ->falseLabel('Inactive Tax Types'),
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
            'index' => Pages\ListIncomeTaxTypes::route('/'),
            'create' => Pages\CreateIncomeTaxType::route('/create'),
            'edit' => Pages\EditIncomeTaxType::route('/{record}/edit'),
        ];
    }
}
