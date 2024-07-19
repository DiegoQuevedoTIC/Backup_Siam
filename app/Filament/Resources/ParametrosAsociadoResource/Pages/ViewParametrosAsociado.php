<?php

namespace App\Filament\Resources\ParametrosAsociadoResource\Pages;

use App\Filament\Resources\ParametrosAsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParametrosAsociado extends ViewRecord
{
    protected static string $resource = ParametrosAsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
