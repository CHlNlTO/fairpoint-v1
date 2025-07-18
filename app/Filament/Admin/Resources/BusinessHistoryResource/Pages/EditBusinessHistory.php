<?php

namespace App\Filament\Admin\Resources\BusinessHistoryResource\Pages;

use App\Filament\Admin\Resources\BusinessHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessHistory extends EditRecord
{
    protected static string $resource = BusinessHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
