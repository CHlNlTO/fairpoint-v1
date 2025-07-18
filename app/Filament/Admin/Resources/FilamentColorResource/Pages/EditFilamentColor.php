<?php

namespace App\Filament\Admin\Resources\FilamentColorResource\Pages;

use App\Filament\Admin\Resources\FilamentColorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilamentColor extends EditRecord
{
    protected static string $resource = FilamentColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
