<?php

namespace App\Filament\Admin\Resources\BusinessRegistrationResource\Pages;

use App\Filament\Admin\Resources\BusinessRegistrationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessRegistration extends EditRecord
{
    protected static string $resource = BusinessRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
