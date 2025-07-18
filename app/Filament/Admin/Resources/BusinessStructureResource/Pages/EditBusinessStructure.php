<?php

namespace App\Filament\Admin\Resources\BusinessStructureResource\Pages;

use App\Filament\Admin\Resources\BusinessStructureResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessStructure extends EditRecord
{
    protected static string $resource = BusinessStructureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
