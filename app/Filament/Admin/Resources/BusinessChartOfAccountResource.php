<?php
// app/Filament/Admin/Resources/BusinessChartOfAccountResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BusinessChartOfAccountResource\Pages;
use App\Models\BusinessChartOfAccount;
use App\Models\Business;
use App\Models\AccountType;
use App\Models\SubAccountType;
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

class BusinessChartOfAccountResource extends Resource
{
    protected static ?string $model = BusinessChartOfAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Business Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Business Information')
                    ->schema([
                        Select::make('business_id')
                            ->label('Business')
                            ->options(fn() => Business::pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select business')
                            ->inlineLabel(),
                    ])
                    ->compact(),

                Section::make('Account Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter account code')
                                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule) {
                                        return $rule->where(function ($query) {
                                            return $query->where('business_id', request()->input('business_id'));
                                        });
                                    })
                                    ->inlineLabel(),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter account name')
                                    ->inlineLabel(),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('account_type_id')
                                    ->label('Account Type')
                                    ->options(fn() => AccountType::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select account type')
                                    ->reactive()
                                    ->afterStateUpdated(fn(callable $set) => $set('sub_account_type_id', null))
                                    ->inlineLabel(),

                                Select::make('sub_account_type_id')
                                    ->label('Sub-Account Type')
                                    ->options(function (callable $get) {
                                        $accountTypeId = $get('account_type_id');
                                        if (!$accountTypeId) {
                                            return [];
                                        }
                                        return SubAccountType::where('account_type_id', $accountTypeId)
                                            ->where('is_active', true)
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select sub-account type')
                                    ->inlineLabel(),
                            ]),

                        Textarea::make('description')
                            ->placeholder('Enter account description')
                            ->rows(3)
                            ->inlineLabel(),

                        Grid::make(3)
                            ->schema([
                                TextInput::make('amount')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('₱')
                                    ->placeholder('Enter amount')
                                    ->inlineLabel(),

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

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_from_template')
                                    ->label('From Template')
                                    ->disabled()
                                    ->inlineLabel(),

                                Select::make('template_item_id')
                                    ->label('Template Item')
                                    ->relationship('templateItem', 'name')
                                    ->disabled()
                                    ->placeholder('N/A')
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

                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('accountType.name')
                    ->label('Account Type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Asset' => 'info',
                        'Liability' => 'warning',
                        'Equity' => 'gray',
                        'Revenue' => 'success',
                        'Expense' => 'danger',
                        'COGS' => 'primary',
                        default => 'secondary',
                    }),

                TextColumn::make('subAccountType.name')
                    ->label('Sub-Account Type')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A')
                    ->toggleable(),

                TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('is_from_template')
                    ->label('From Template')
                    ->badge()
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                    ->toggleable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('business_id')
                    ->label('Business')
                    ->options(fn() => Business::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('account_type_id')
                    ->label('Account Type')
                    ->options(fn() => AccountType::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('sub_account_type_id')
                    ->label('Sub-Account Type')
                    ->options(fn() => SubAccountType::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_from_template')
                    ->label('From Template')
                    ->placeholder('All Accounts')
                    ->trueLabel('Template Accounts')
                    ->falseLabel('Custom Accounts'),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Accounts')
                    ->trueLabel('Active Accounts')
                    ->falseLabel('Inactive Accounts'),
            ])
            ->defaultSort('code', 'asc');
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
            'index' => Pages\ListBusinessChartOfAccounts::route('/'),
            'create' => Pages\CreateBusinessChartOfAccount::route('/create'),
            'edit' => Pages\EditBusinessChartOfAccount::route('/{record}/edit'),
        ];
    }
}
