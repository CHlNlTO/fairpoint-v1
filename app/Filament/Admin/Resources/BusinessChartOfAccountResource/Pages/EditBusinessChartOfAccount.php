<?php

namespace App\Filament\Admin\Resources\BusinessChartOfAccountResource\Pages;

use App\Filament\Admin\Resources\BusinessChartOfAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessChartOfAccount extends EditRecord
{
    protected static string $resource = BusinessChartOfAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
