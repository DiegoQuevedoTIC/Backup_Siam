<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\RedOficinasCorresponsalesBancariosResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\RedOficinasCorresponsalesBancariosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRedOficinasCorresponsalesBancarios extends ListRecords
{
    protected static string $resource = RedOficinasCorresponsalesBancariosResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
