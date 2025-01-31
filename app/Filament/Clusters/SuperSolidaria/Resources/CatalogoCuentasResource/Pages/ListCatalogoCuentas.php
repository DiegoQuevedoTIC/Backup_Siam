<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\CatalogoCuentasResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\CatalogoCuentasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatalogoCuentas extends ListRecords
{
    protected static string $resource = CatalogoCuentasResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
