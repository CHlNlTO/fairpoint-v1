<?php

namespace App\Filament\Admin\Resources\AccountTypeResource\Pages;

use App\Filament\Admin\Resources\AccountTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccountType extends CreateRecord
{
    protected static string $resource = AccountTypeResource::class;
}
