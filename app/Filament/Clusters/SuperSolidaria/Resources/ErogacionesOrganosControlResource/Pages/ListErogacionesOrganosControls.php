<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\ErogacionesOrganosControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListErogacionesOrganosControls extends ListRecords
{
    protected static string $resource = ErogacionesOrganosControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
