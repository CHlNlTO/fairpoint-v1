<?php
// app/Filament/Admin/Resources/BusinessStatusResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessStatusResource\Pages;
use App\Models\BusinessStatus;
use App\Models\FilamentColor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class BusinessStatusResource extends Resource
{
    protected static ?string $model = BusinessStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationGroup = 'Business Configuration';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Business Status Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter status name')
                                    ->inlineLabel(),

                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter unique code (e.g., ACTIVE)')
                                    ->unique(ignoreRecord: true)
                                    ->inlineLabel(),
                            ]),

                        Select::make('color_id')
                            ->label('Status Color')
                            ->options(FilamentColor::where('is_active', true)->pluck('label', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select status color')
                            ->inlineLabel(),

                        Textarea::make('description')
                            ->placeholder('Enter status description')
                            ->rows(3)
                            ->inlineLabel(),

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
                    ->color(fn($record) => $record->color?->class_name ?? 'gray'),

                TextColumn::make('color.label')
                    ->label('Color')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                ColorColumn::make('color.hex_value')
                    ->label('Color Preview')
                    ->toggleable(),

                TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('N/A')
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
                SelectFilter::make('color_id')
                    ->label('Color')
                    ->options(FilamentColor::pluck('label', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Statuses')
                    ->trueLabel('Active Statuses')
                    ->falseLabel('Inactive Statuses'),
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
            'index' => Pages\ListBusinessStatuses::route('/'),
            'create' => Pages\CreateBusinessStatus::route('/create'),
            'edit' => Pages\EditBusinessStatus::route('/{record}/edit'),
        ];
    }
}
