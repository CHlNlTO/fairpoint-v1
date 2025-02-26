<?php

namespace App\Filament\Admin\Resources\SalesTransactionResource\Pages;

use App\Filament\Admin\Resources\SalesTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesTransactions extends ListRecords
{
    protected static string $resource = SalesTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
