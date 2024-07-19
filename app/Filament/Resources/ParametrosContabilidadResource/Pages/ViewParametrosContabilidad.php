<?php

namespace App\Filament\Resources\ParametrosContabilidadResource\Pages;

use App\Filament\Resources\ParametrosContabilidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewParametrosContabilidad extends ViewRecord
{
    protected static string $resource = ParametrosContabilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
