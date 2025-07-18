<?php

namespace App\Filament\Admin\Resources\BusinessTaxInformationResource\Pages;

use App\Filament\Admin\Resources\BusinessTaxInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessTaxInformation extends EditRecord
{
    protected static string $resource = BusinessTaxInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
