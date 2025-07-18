<?php

namespace App\Filament\Admin\Resources\RegistrationTypeResource\Pages;

use App\Filament\Admin\Resources\RegistrationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegistrationType extends EditRecord
{
    protected static string $resource = RegistrationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
