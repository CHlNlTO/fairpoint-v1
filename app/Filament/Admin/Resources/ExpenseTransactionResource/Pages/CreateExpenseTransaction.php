<?php

namespace App\Filament\Admin\Resources\ExpenseTransactionResource\Pages;

use App\Filament\Admin\Resources\ExpenseTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpenseTransaction extends CreateRecord
{
    protected static string $resource = ExpenseTransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
