<?php

namespace App\Filament\Resources\Reportes\AnexosTributariosResource\Pages;

use App\Filament\Resources\Reportes\AnexosTributariosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnexosTributarios extends ListRecords
{
    protected static string $resource = AnexosTributariosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
