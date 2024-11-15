<?php

namespace App\Filament\Resources\InscripcionActividadesResource\Pages;

use App\Filament\Resources\InscripcionActividadesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInscripcionActividades extends ListRecords
{
    protected static string $resource = InscripcionActividadesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
