<?php

namespace App\Filament\Resources\GestionAsociadoResource\Pages;

use App\Filament\Resources\GestionAsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGestionAsociados extends ListRecords
{
    protected static string $resource = GestionAsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
