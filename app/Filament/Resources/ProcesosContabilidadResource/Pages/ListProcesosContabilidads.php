<?php

namespace App\Filament\Resources\ProcesosContabilidadResource\Pages;

use App\Filament\Resources\ProcesosContabilidadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProcesosContabilidads extends ListRecords
{
    protected static string $resource = ProcesosContabilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
