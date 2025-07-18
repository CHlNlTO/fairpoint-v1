<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateItemResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChartOfAccountsTemplateItems extends ListRecords
{
    protected static string $resource = ChartOfAccountsTemplateItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
