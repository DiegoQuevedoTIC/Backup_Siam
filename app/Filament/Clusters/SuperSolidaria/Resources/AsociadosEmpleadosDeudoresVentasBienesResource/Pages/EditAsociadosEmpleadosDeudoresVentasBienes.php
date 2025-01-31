<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\AsociadosEmpleadosDeudoresVentasBienesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsociadosEmpleadosDeudoresVentasBienes extends EditRecord
{
    protected static string $resource = AsociadosEmpleadosDeudoresVentasBienesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
