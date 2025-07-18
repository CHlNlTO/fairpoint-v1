<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateHistoryResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChartOfAccountsTemplateHistories extends ListRecords
{
    protected static string $resource = ChartOfAccountsTemplateHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
