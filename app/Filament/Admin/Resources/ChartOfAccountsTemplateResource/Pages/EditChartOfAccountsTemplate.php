<?php

namespace App\Filament\Admin\Resources\ChartOfAccountsTemplateResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountsTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChartOfAccountsTemplate extends EditRecord
{
    protected static string $resource = ChartOfAccountsTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
