<?php

namespace App\Filament\Resources\TipoIdentificacionResource\Pages;

use App\Filament\Resources\TipoIdentificacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoIdentificacions extends ManageRecords
{
    protected static string $resource = TipoIdentificacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo de Identificacion'),
        ];
    }
}
