<?php

namespace App\Filament\Resources\InscripcionActividadesResource\Pages;

use App\Filament\Resources\InscripcionActividadesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInscripcionActividades extends EditRecord
{
    protected static string $resource = InscripcionActividadesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
