<?php

namespace App\Filament\Admin\Resources\BusinessUserResource\Pages;

use App\Filament\Admin\Resources\BusinessUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessUsers extends ListRecords
{
    protected static string $resource = BusinessUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
