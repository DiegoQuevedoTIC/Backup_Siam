<?php

namespace App\Filament\Resources\TipoResidenciaResource\Pages;

use App\Filament\Resources\TipoResidenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoResidencias extends ManageRecords
{
    protected static string $resource = TipoResidenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo de Vivienda'),
        ];
    }
}
