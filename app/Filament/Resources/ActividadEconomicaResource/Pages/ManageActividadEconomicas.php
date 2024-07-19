<?php

namespace App\Filament\Resources\ActividadEconomicaResource\Pages;

use App\Filament\Resources\ActividadEconomicaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageActividadEconomicas extends ManageRecords
{
    protected static string $resource = ActividadEconomicaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Actividad Economica'),
        ];
    }
}
