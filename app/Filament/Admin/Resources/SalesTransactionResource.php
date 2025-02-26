<?php
// app/Filament/Admin/Resources/SalesTransactionResource.php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SalesTransactionResource\Pages;
use App\Models\SalesTransaction;
use App\Models\Customer;
use App\Models\ChartOfAccount;
use App\Models\PaymentMethod;
use App\Models\Project;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SalesTransactionResource extends Resource
{
    protected static ?string $model = SalesTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'or_number';

    public static function form(Form $form): Form
    {
        $currentYear = Carbon::now()->year;
        $years = [];
        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
            $years[$i] = $i;
        }

        return $form
            ->schema([
                Section::make('Transaction Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('or_number')
                                    ->label('OR Number')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter OR number')
                                    ->unique(ignoreRecord: true)
                                    ->autofocus(),

                                DatePicker::make('transaction_date')
                                    ->required()
                                    ->format('M d, Y')
                                    ->default(now())
                                    ->placeholder('Select transaction date'),
                            ]),
                    ]),

                Section::make('Customer Information')
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn() => Customer::where('is_active', true)->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Select customer'),

                        Grid::make(2)
                            ->schema([
                                Select::make('account_id')
                                    ->label('Revenue Account')
                                    ->options(fn() => ChartOfAccount::where('type', 'Revenue')->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select revenue account'),

                                Select::make('payment_method_id')
                                    ->label('Payment Method')
                                    ->options(fn() => PaymentMethod::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select payment method'),
                            ]),
                    ]),

                Section::make('Amount Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0.01)
                                    ->step(0.01)
                                    ->placeholder('Enter amount')
                                    ->prefix('₱'),

                                Textarea::make('particulars')
                                    ->required()
                                    ->placeholder('Enter transaction particulars')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('project_id')
                                    ->label('Project')
                                    ->options(fn() => Project::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select project'),

                                Select::make('quarter')
                                    ->options([
                                        'Q1' => 'Q1 (Jan-Mar)',
                                        'Q2' => 'Q2 (Apr-Jun)',
                                        'Q3' => 'Q3 (Jul-Sep)',
                                        'Q4' => 'Q4 (Oct-Dec)',
                                    ])
                                    ->placeholder('Select quarter'),

                                Select::make('fiscal_year')
                                    ->options($years)
                                    ->default($currentYear)
                                    ->placeholder('Select fiscal year'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('or_number')
                    ->label('OR Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('transaction_date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),

                TextColumn::make('particulars')
                    ->limit(30)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('account.name')
                    ->label('Revenue Account')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('paymentMethod.name')
                    ->label('Payment Method')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('N/A'),

                TextColumn::make('quarter')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('N/A'),

                TextColumn::make('fiscal_year')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('N/A'),

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
                SelectFilter::make('customer_id')
                    ->label('Customer')
                    ->options(fn() => Customer::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('account_id')
                    ->label('Revenue Account')
                    ->options(fn() => ChartOfAccount::where('type', 'Revenue')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('payment_method_id')
                    ->label('Payment Method')
                    ->options(fn() => PaymentMethod::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(fn() => Project::pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                SelectFilter::make('quarter')
                    ->options([
                        'Q1' => 'Q1 (Jan-Mar)',
                        'Q2' => 'Q2 (Apr-Jun)',
                        'Q3' => 'Q3 (Jul-Sep)',
                        'Q4' => 'Q4 (Oct-Dec)',
                    ]),

                SelectFilter::make('fiscal_year')
                    ->options(function () {
                        $currentYear = Carbon::now()->year;
                        $years = [];
                        for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    }),

                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->format('M d, Y')
                            ->placeholder('From date'),
                        DatePicker::make('until')
                            ->label('Until Date')
                            ->format('M d, Y')
                            ->placeholder('Until date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('transaction_date', '<=', $date),
                            );
                    }),
            ])
            ->defaultSort('transaction_date', 'desc')
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
            'index' => Pages\ListSalesTransactions::route('/'),
            'create' => Pages\CreateSalesTransaction::route('/create'),
            'edit' => Pages\EditSalesTransaction::route('/{record}/edit'),
        ];
    }
}
