<?php

namespace App\Filament\Admin\Resources\BusinessTaxInformationResource\Pages;

use App\Filament\Admin\Resources\BusinessTaxInformationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessTaxInformation extends ListRecords
{
    protected static string $resource = BusinessTaxInformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
