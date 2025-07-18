<?php

namespace App\Filament\Admin\Resources\BusinessChartOfAccountResource\Pages;

use App\Filament\Admin\Resources\BusinessChartOfAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessChartOfAccounts extends ListRecords
{
    protected static string $resource = BusinessChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
