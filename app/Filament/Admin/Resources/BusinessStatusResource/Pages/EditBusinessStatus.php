<?php

namespace App\Filament\Admin\Resources\BusinessStatusResource\Pages;

use App\Filament\Admin\Resources\BusinessStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessStatus extends EditRecord
{
    protected static string $resource = BusinessStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
