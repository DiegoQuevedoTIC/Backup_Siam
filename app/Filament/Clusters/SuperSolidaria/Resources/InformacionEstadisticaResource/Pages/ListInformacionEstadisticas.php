<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformacionEstadisticaResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformacionEstadisticaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformacionEstadisticas extends ListRecords
{
    protected static string $resource = InformacionEstadisticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
