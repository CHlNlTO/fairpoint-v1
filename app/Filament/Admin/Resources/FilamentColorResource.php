<?php
// app/Filament/Admin/Resources/FilamentColorResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FilamentColorResource\Pages;
use App\Models\FilamentColor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Filters\TernaryFilter;

class FilamentColorResource extends Resource
{
    protected static ?string $model = FilamentColor::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static ?string $navigationGroup = 'System Configuration';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Color Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Enter color identifier (e.g., primary)')
                                    ->helperText('System identifier for the color')
                                    ->inlineLabel(),

                                TextInput::make('label')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter display name (e.g., Primary)')
                                    ->helperText('User-friendly name')
                                    ->inlineLabel(),

                                TextInput::make('class_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter CSS class (e.g., primary)')
                                    ->helperText('Filament color class')
                                    ->inlineLabel(),

                                TextInput::make('hex_value')
                                    ->maxLength(7)
                                    ->placeholder('Enter hex color (e.g., #3b82f6)')
                                    ->helperText('Optional hex representation')
                                    ->prefix('#')
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->helperText('Enable or disable this color')
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

                TextColumn::make('label')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('class_name')
                    ->label('CSS Class')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(FilamentColor $record): string => $record->class_name),

                ColorColumn::make('hex_value')
                    ->label('Color Preview')
                    ->placeholder('N/A')
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('businessStatuses_count')
                    ->label('Used in Statuses')
                    ->counts('businessStatuses')
                    ->badge()
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
                    ->label('Status')
                    ->placeholder('All Colors')
                    ->trueLabel('Active Colors')
                    ->falseLabel('Inactive Colors'),
            ])
            ->defaultSort('name', 'asc');
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
            'index' => Pages\ListFilamentColors::route('/'),
            'create' => Pages\CreateFilamentColor::route('/create'),
            'edit' => Pages\EditFilamentColor::route('/{record}/edit'),
        ];
    }
}
