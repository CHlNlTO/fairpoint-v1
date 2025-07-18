<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChartOfAccountsTemplates extends ListRecords
{
    protected static string $resource = ChartOfAccountsTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
