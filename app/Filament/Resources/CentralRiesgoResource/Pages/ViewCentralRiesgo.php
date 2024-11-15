<?php

namespace App\Filament\Resources\CentralRiesgoResource\Pages;

use App\Filament\Resources\CentralRiesgoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCentralRiesgo extends ViewRecord
{
    protected static string $resource = CentralRiesgoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
