<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformacionEstadisticaResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformacionEstadisticaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInformacionEstadistica extends EditRecord
{
    protected static string $resource = InformacionEstadisticaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
