<?php

namespace App\Filament\Admin\Resources\ExpenseTransactionResource\Pages;

use App\Filament\Admin\Resources\ExpenseTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpenseTransaction extends EditRecord
{
    protected static string $resource = ExpenseTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
