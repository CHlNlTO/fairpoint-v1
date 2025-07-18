<?php

namespace App\Filament\Admin\Resources\BusinessRegistrationProgressResource\Pages;

use App\Filament\Admin\Resources\BusinessRegistrationProgressResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessRegistrationProgress extends ListRecords
{
    protected static string $resource = BusinessRegistrationProgressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
