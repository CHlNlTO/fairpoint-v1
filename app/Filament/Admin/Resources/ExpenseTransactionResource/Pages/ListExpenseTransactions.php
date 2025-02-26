<?php

namespace App\Filament\Admin\Resources\ExpenseTransactionResource\Pages;

use App\Filament\Admin\Resources\ExpenseTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExpenseTransactions extends ListRecords
{
    protected static string $resource = ExpenseTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
