<?php

namespace App\Filament\Resources\TipoEntidadResource\Pages;

use App\Filament\Resources\TipoEntidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoEntidads extends ManageRecords
{
    protected static string $resource = TipoEntidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo de Entidad'),
        ];
    }
}
