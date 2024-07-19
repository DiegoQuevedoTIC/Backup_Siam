<?php

namespace App\Filament\Resources\ParametrosTerceroResource\Pages;

use App\Filament\Resources\ParametrosTerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParametrosTercero extends ViewRecord
{
    protected static string $resource = ParametrosTerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
