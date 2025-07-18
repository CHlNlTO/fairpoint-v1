<?php
// app/Filament/Admin/Resources/ChartOfAccountsTemplateResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateResource\Pages;
use App\Models\ChartOfAccountsTemplate;
use App\Models\Industry;
use App\Models\BusinessStructure;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class ChartOfAccountsTemplateResource extends Resource
{
    protected static ?string $model = ChartOfAccountsTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Chart of Accounts Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Template Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter template name')
                            ->inlineLabel(),

                        Grid::make(2)
                            ->schema([
                                Select::make('industry_id')
                                    ->label('Industry')
                                    ->options(fn() => Industry::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select industry')
                                    ->inlineLabel(),

                                Select::make('business_structure_id')
                                    ->label('Business Structure')
                                    ->options(fn() => BusinessStructure::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business structure')
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('version')
                                    ->required()
                                    ->default('1.0')
                                    ->placeholder('Enter version number')
                                    ->inlineLabel(),

                                Toggle::make('is_default')
                                    ->label('Set as Default')
                                    ->helperText('Default template for this industry/structure combination')
                                    ->inlineLabel(),
                            ]),

                        Textarea::make('description')
                            ->placeholder('Enter template description')
                            ->rows(3)
                            ->inlineLabel(),

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

                TextColumn::make('industry.name')
                    ->label('Industry')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('businessStructure.name')
                    ->label('Business Structure')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('version')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                ToggleColumn::make('is_default')
                    ->label('Default')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
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
                SelectFilter::make('industry_id')
                    ->label('Industry')
                    ->options(fn() => Industry::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('business_structure_id')
                    ->label('Business Structure')
                    ->options(fn() => BusinessStructure::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_default')
                    ->label('Default Status')
                    ->placeholder('All Templates')
                    ->trueLabel('Default Templates')
                    ->falseLabel('Non-Default Templates'),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Templates')
                    ->trueLabel('Active Templates')
                    ->falseLabel('Inactive Templates'),
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
            'index' => Pages\ListChartOfAccountsTemplates::route('/'),
            'create' => Pages\CreateChartOfAccountsTemplate::route('/create'),
            'edit' => Pages\EditChartOfAccountsTemplate::route('/{record}/edit'),
        ];
    }
}
