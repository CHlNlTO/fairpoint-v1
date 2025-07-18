<?php

namespace App\Filament\Admin\Resources\BusinessHistoryResource\Pages;

use App\Filament\Admin\Resources\BusinessHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessHistories extends ListRecords
{
    protected static string $resource = BusinessHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
