<?php

namespace App\Filament\Admin\Resources\BusinessUserStatusResource\Pages;

use App\Filament\Admin\Resources\BusinessUserStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessUserStatuses extends ListRecords
{
    protected static string $resource = BusinessUserStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
