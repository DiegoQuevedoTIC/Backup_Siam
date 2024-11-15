<?php

namespace App\Filament\Resources\InscripcionActividadesResource\Pages;

use App\Filament\Resources\InscripcionActividadesResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInscripcionActividades extends ViewRecord
{
    protected static string $resource = InscripcionActividadesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
