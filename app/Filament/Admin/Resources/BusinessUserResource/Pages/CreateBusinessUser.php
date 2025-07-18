<?php

namespace App\Filament\Admin\Resources\BusinessUserResource\Pages;

use App\Filament\Admin\Resources\BusinessUserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBusinessUser extends CreateRecord
{
    protected static string $resource = BusinessUserResource::class;
}
