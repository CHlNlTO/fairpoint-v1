<?php
// app/Filament/Admin/Resources/ChartOfAccountResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChartOfAccountResource\Pages;
use App\Models\ChartOfAccount;
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
// use Illuminate\Database\Eloquent\Builder;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Accounting';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Account Information')
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('Enter account code')
                                    ->autofocus()
                                    ->inlineLabel(),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter account name')
                                    ->inlineLabel(),

                                Select::make('type')
                                    ->options([
                                        'Revenue' => 'Revenue',
                                        'Expense' => 'Expense',
                                        'Cash' => 'Cash',
                                        'Payable' => 'Payable',
                                        'Receivable' => 'Receivable',
                                        'Asset' => 'Asset',
                                        'Liability' => 'Liability',
                                        'Equity' => 'Equity',
                                    ])
                                    ->default('Revenue')
                                    ->required()
                                    ->placeholder('Select account type')
                                    ->inlineLabel(),
                                Textarea::make('description')
                                    ->placeholder('Enter account description')
                                    ->inlineLabel(),
                                Toggle::make('is_active')
                                    ->default(true)
                                    ->label('Active')
                                    ->inlineLabel(),
                            ]),


                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Revenue' => 'success',
                        'Expense' => 'danger',
                        'Cash' => 'info',
                        'Payable' => 'warning',
                        'Receivable' => 'primary',
                        'Asset' => 'info',
                        'Liability' => 'warning',
                        'Equity' => 'gray',
                    }),

                TextColumn::make('description')
                    ->placeholder('N/A')
                    ->toggleable()
                    ->limit(50),

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
                SelectFilter::make('type')
                    ->options([
                        'Revenue' => 'Revenue',
                        'Expense' => 'Expense',
                        'Cash' => 'Cash',
                        'Payable' => 'Payable',
                        'Receivable' => 'Receivable',
                        'Asset' => 'Asset',
                        'Liability' => 'Liability',
                        'Equity' => 'Equity',
                    ]),

                SelectFilter::make('is_active')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ])
                    ->label('Status'),
            ])
            ->defaultSort('code', 'asc')
            ->searchable();
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
            'index' => Pages\ListChartOfAccounts::route('/'),
            'create' => Pages\CreateChartOfAccount::route('/create'),
            'edit' => Pages\EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
