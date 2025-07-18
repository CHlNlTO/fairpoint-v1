<?php
// app/Filament/Admin/Resources/BusinessResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessResource\Pages;
use App\Models\Business;
use App\Models\BusinessType;
use App\Models\BusinessStructure;
use App\Models\Industry;
use App\Models\BusinessStatus;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Yajra\Address\Entities\Province;
use Yajra\Address\Entities\City;
use Yajra\Address\Entities\Barangay;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Business Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter business name')
                                    ->helperText('If no business name, user name will be default')
                                    ->inlineLabel(),

                                TextInput::make('tin')
                                    ->label('TIN')
                                    ->maxLength(255)
                                    ->placeholder('000-000-000-000')
                                    ->mask('999-999-999-999')
                                    ->inlineLabel(),
                            ]),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Enter business email')
                            ->inlineLabel(),
                    ])
                    ->compact(),

                Section::make('Business Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('business_type_id')
                                    ->label('Business Type')
                                    ->options(fn() => BusinessType::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business type')
                                    ->inlineLabel(),

                                Select::make('business_structure_id')
                                    ->label('Business Structure')
                                    ->options(fn() => BusinessStructure::where('is_active', true)->ordered()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select business structure')
                                    ->inlineLabel(),
                            ]),

                        Select::make('industry_id')
                            ->label('Industry')
                            ->options(fn() => Industry::where('is_active', true)->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->placeholder('Select industry')
                            ->inlineLabel(),
                    ])
                    ->compact(),

                Section::make('Business Address')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('address_sub_street')
                                    ->label('Sub-Street/Building')
                                    ->maxLength(255)
                                    ->placeholder('Enter building/unit number')
                                    ->inlineLabel(),

                                TextInput::make('address_street')
                                    ->label('Street')
                                    ->maxLength(255)
                                    ->placeholder('Enter street name')
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('province_id')
                                    ->label('Province')
                                    ->options(fn() => Province::pluck('name', 'province_id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select province')
                                    ->reactive()
                                    ->inlineLabel(),

                                Select::make('city_id')
                                    ->label('City/Municipality')
                                    ->options(function (Get $get) {
                                        $provinceId = $get('province_id');
                                        if (!$provinceId) {
                                            return [];
                                        }
                                        return City::where('province_id', $provinceId)->pluck('name', 'city_id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select city/municipality')
                                    ->reactive()
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('barangay_id')
                                    ->label('Barangay')
                                    ->options(function (Get $get) {
                                        $cityId = $get('city_id');
                                        if (!$cityId) {
                                            return [];
                                        }
                                        return Barangay::where('city_id', $cityId)->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select barangay')
                                    ->inlineLabel(),

                                TextInput::make('zip_code')
                                    ->label('ZIP Code')
                                    ->maxLength(10)
                                    ->placeholder('Enter ZIP code')
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Fiscal Year')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('fiscal_year_start')
                                    ->label('Fiscal Year Start')
                                    ->format('M d')
                                    ->displayFormat('M d')
                                    ->placeholder('Select start date')
                                    ->inlineLabel(),

                                DatePicker::make('fiscal_year_end')
                                    ->label('Fiscal Year End')
                                    ->format('M d')
                                    ->displayFormat('M d')
                                    ->placeholder('Select end date')
                                    ->inlineLabel(),
                            ]),
                    ])
                    ->compact(),

                Section::make('Status')
                    ->schema([
                        Select::make('status_id')
                            ->label('Business Status')
                            ->options(fn() => BusinessStatus::where('is_active', true)->ordered()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select status')
                            ->default(fn() => BusinessStatus::where('code', 'DRAFT')->first()?->id)
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

                TextColumn::make('tin')
                    ->label('TIN')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),

                TextColumn::make('businessType.name')
                    ->label('Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('businessStructure.name')
                    ->label('Structure')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('industry.name')
                    ->label('Industry')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => $record->status?->color?->class_name ?? 'gray'),

                TextColumn::make('fiscal_year_start')
                    ->label('Fiscal Year')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->fiscal_year_start && $record->fiscal_year_end
                            ? $record->fiscal_year_start->format('M d') . ' - ' . $record->fiscal_year_end->format('M d')
                            : 'N/A'
                    )
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
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
                SelectFilter::make('business_type_id')
                    ->label('Business Type')
                    ->options(fn() => BusinessType::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('business_structure_id')
                    ->label('Business Structure')
                    ->options(fn() => BusinessStructure::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('industry_id')
                    ->label('Industry')
                    ->options(fn() => Industry::where('is_active', true)->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(fn() => BusinessStatus::where('is_active', true)->ordered()->pluck('name', 'id'))
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
