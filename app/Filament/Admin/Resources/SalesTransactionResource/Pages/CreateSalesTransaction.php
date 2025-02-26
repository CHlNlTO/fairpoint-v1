<?php

namespace App\Filament\Admin\Resources\SalesTransactionResource\Pages;

use App\Filament\Admin\Resources\SalesTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesTransaction extends CreateRecord
{
    protected static string $resource = SalesTransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
