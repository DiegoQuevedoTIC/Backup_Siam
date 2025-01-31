<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\AnexoCarteraResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\AnexoCarteraResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAnexoCarteras extends ListRecords
{
    protected static string $resource = AnexoCarteraResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
