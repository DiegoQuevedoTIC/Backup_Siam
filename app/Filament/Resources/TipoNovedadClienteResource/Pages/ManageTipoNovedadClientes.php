<?php

namespace App\Filament\Resources\TipoNovedadClienteResource\Pages;

use App\Filament\Resources\TipoNovedadClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoNovedadClientes extends ManageRecords
{
    protected static string $resource = TipoNovedadClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo Novedad'),
        ];
    }
}
