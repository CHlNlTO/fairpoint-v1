<?php

namespace App\Filament\Admin\Resources\IncomeTaxTypeResource\Pages;

use App\Filament\Admin\Resources\IncomeTaxTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIncomeTaxTypes extends ListRecords
{
    protected static string $resource = IncomeTaxTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
