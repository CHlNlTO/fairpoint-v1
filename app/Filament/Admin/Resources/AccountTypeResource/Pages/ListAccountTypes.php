<?php

namespace App\Filament\Admin\Resources\AccountTypeResource\Pages;

use App\Filament\Admin\Resources\AccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAccountTypes extends ListRecords
{
    protected static string $resource = AccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
