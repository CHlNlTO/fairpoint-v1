<?php

namespace App\Filament\Admin\Resources\SubAccountTypeResource\Pages;

use App\Filament\Admin\Resources\SubAccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubAccountType extends EditRecord
{
    protected static string $resource = SubAccountTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
