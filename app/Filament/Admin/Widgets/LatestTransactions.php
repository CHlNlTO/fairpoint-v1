<?php
// app/Filament/Widgets/LatestTransactions.php

namespace App\Filament\Admin\Widgets;

use App\Models\SalesTransaction;
use App\Models\ExpenseTransaction;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\DB;

class LatestTransactions extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Union of sales and expense transactions, ordered by date
                SalesTransaction::query()
                    ->select(
                        'id',
                        'transaction_date',
                        'or_number as reference',
                        'customer_id',
                        'particulars',
                        'amount',
                        'account_id',
                        DB::raw("'sale' as transaction_type")
                    )
                    ->limit(10)
                    ->orderBy('transaction_date', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reference')
                    ->label('Reference'),

                Tables\Columns\TextColumn::make('transaction_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'sale' => 'success',
                        'expense' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer/Vendor')
                    ->getStateUsing(function ($record) {
                        if ($record->transaction_type === 'sale') {
                            return $record->customer?->name ?? 'Unknown Customer';
                        } else {
                            $expense = ExpenseTransaction::find($record->id);
                            return $expense?->vendor?->name ?? $expense?->vendor_name ?? 'Unknown Vendor';
                        }
                    }),

                Tables\Columns\TextColumn::make('particulars')
                    ->limit(30),

                Tables\Columns\TextColumn::make('amount')
                    ->money('PHP')
                    ->sortable(),

                Tables\Columns\TextColumn::make('account.name')
                    ->label('Account'),
            ])
            ->heading('Latest Transactions')
            ->defaultSort('transaction_date', 'desc');
    }
}
