<?php

namespace App\Filament\Admin\Resources\BusinessRegistrationProgressResource\Pages;

use App\Filament\Admin\Resources\BusinessRegistrationProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessRegistrationProgress extends EditRecord
{
    protected static string $resource = BusinessRegistrationProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
