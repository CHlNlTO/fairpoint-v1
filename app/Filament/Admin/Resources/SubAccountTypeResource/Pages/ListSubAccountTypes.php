<?php

namespace App\Filament\Admin\Resources\SubAccountTypeResource\Pages;

use App\Filament\Admin\Resources\SubAccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubAccountTypes extends ListRecords
{
    protected static string $resource = SubAccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
