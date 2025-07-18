<?php

namespace App\Filament\Admin\Resources\FilamentColorResource\Pages;

use App\Filament\Admin\Resources\FilamentColorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFilamentColors extends ListRecords
{
    protected static string $resource = FilamentColorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
