<?php

namespace App\Filament\Resources\TipoContratoResource\Pages;

use App\Filament\Resources\TipoContratoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoContratos extends ManageRecords
{
    protected static string $resource = TipoContratoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo de Contrato'),
        ];
    }
}
