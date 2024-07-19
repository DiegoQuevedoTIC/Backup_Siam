<?php

namespace App\Filament\Resources\ProcesosContabilidadResource\Pages;

use App\Filament\Resources\ProcesosContabilidadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProcesosContabilidad extends EditRecord
{
    protected static string $resource = ProcesosContabilidadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
