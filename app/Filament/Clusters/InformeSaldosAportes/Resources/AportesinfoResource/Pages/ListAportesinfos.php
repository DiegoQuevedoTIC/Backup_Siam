<?php

namespace App\Filament\Clusters\InformeSaldosAportes\Resources\AportesinfoResource\Pages;

use App\Filament\Clusters\InformeSaldosAportes\Resources\AportesinfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAportesinfos extends ListRecords
{
    protected static string $resource = AportesinfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
