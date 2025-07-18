<?php

namespace App\Filament\Admin\Resources\BusinessStructureResource\Pages;

use App\Filament\Admin\Resources\BusinessStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessStructures extends ListRecords
{
    protected static string $resource = BusinessStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
