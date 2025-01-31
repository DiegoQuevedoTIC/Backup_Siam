<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\RelacionPropiedadPlantaEquipoResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\RelacionPropiedadPlantaEquipoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelacionPropiedadPlantaEquipo extends EditRecord
{
    protected static string $resource = RelacionPropiedadPlantaEquipoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
