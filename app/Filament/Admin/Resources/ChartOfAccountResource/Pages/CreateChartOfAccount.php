<?php

namespace App\Filament\Admin\Resources\ChartOfAccountResource\Pages;

use App\Filament\Admin\Resources\ChartOfAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChartOfAccount extends CreateRecord
{
    protected static string $resource = ChartOfAccountResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
