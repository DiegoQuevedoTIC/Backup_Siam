<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsociadosEmpleadosDeudoresVentasBienes extends ListRecords
{
    protected static string $resource = AsociadosEmpleadosDeudoresVentasBienesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
