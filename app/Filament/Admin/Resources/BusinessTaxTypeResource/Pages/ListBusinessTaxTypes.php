<?php

namespace App\Filament\Admin\Resources\BusinessTaxTypeResource\Pages;

use App\Filament\Admin\Resources\BusinessTaxTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessTaxTypes extends ListRecords
{
    protected static string $resource = BusinessTaxTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
