<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateHistoryResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChartOfAccountsTemplateHistory extends EditRecord
{
    protected static string $resource = ChartOfAccountsTemplateHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
