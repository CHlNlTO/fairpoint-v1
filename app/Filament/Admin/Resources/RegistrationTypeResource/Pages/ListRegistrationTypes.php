<?php

namespace App\Filament\Admin\Resources\RegistrationTypeResource\Pages;

use App\Filament\Admin\Resources\RegistrationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegistrationTypes extends ListRecords
{
    protected static string $resource = RegistrationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
