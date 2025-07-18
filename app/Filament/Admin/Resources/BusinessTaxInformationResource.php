<?php
// app/Filament/Admin/Resources/BusinessTaxInformationResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessTaxInformationResource\Pages;
use App\Models\BusinessTaxInformation;
use App\Models\Business;
use App\Models\IncomeTaxType;
use App\Models\BusinessTaxType;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;

class BusinessTaxInformationResource extends Resource
{
    protected static ?string $model = BusinessTaxInformation::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Business Selection')
                    ->schema([
                        Select::make('business_id')
                            ->label('Business')
                            ->options(fn() => Business::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select business')
                            ->unique(ignoreRecord: true)
                            ->helperText('Each business can only have one tax information record')
                            ->inlineLabel(),
                    ])
                    ->compact(),

                Section::make('Tax Type Configuration')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('income_tax_type_id')
                                    ->label('Income Tax Type')
                                    ->options(fn() => IncomeTaxType::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select income tax type')
                                    ->inlineLabel(),

                                Select::make('business_tax_type_id')
                                    ->label('Business Tax Type')
                                    ->options(fn() => BusinessTaxType::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business tax type')
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Tax Settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Toggle::make('with_1601c')
                                    ->label('With 1601C')
                                    ->helperText('Monthly Remittance Return of Income Taxes Withheld on Compensation')
                                    ->inlineLabel(),

                                Toggle::make('with_ewt')
                                    ->label('With EWT')
                                    ->helperText('Expanded Withholding Tax')
                                    ->inlineLabel(),

                                Toggle::make('tamp')
                                    ->label('TAMP')
                                    ->helperText('Tax Administration and Management Program')
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
                TextColumn::make('business.name')
                    ->label('Business')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('incomeTaxType.name')
                    ->label('Income Tax Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->badge()
                    ->color('info'),

                TextColumn::make('businessTaxType.name')
                    ->label('Business Tax Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->badge()
                    ->color('warning'),

                IconColumn::make('with_1601c')
                    ->label('1601C')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('with_ewt')
                    ->label('EWT')
                    ->boolean()
                    ->sortable(),

                IconColumn::make('tamp')
                    ->label('TAMP')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('income_tax_rate')
                    ->label('Income Tax Rate')
                    ->formatStateUsing(fn($state) => $state ? "{$state}%" : 'N/A')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('business_tax_rate')
                    ->label('Business Tax Rate')
                    ->formatStateUsing(fn($state) => $state ? "{$state}%" : 'N/A')
                    ->sortable()
                    ->toggleable(),

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
                SelectFilter::make('income_tax_type_id')
                    ->label('Income Tax Type')
                    ->options(fn() => IncomeTaxType::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('business_tax_type_id')
                    ->label('Business Tax Type')
                    ->options(fn() => BusinessTaxType::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBusinessTaxInformation::route('/'),
            'create' => Pages\CreateBusinessTaxInformation::route('/create'),
            'edit' => Pages\EditBusinessTaxInformation::route('/{record}/edit'),
        ];
    }
}
