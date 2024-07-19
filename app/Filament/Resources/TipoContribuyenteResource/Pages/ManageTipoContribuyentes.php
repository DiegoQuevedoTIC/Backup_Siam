<?php

namespace App\Filament\Resources\TipoContribuyenteResource\Pages;

use App\Filament\Resources\TipoContribuyenteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoContribuyentes extends ManageRecords
{
    protected static string $resource = TipoContribuyenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Tipo de Contribuyente'),
        ];
    }
}
