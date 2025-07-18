<?php

namespace App\Filament\Admin\Resources\BusinessUserResource\Pages;

use App\Filament\Admin\Resources\BusinessUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusinessUser extends EditRecord
{
    protected static string $resource = BusinessUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
