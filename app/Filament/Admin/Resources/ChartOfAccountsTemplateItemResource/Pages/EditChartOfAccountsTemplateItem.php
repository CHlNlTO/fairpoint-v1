<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateItemResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChartOfAccountsTemplateItem extends EditRecord
{
    protected static string $resource = ChartOfAccountsTemplateItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
