<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformeOrganosDirecciónControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformeOrganosDirecciónControls extends ListRecords
{
    protected static string $resource = InformeOrganosDirecciónControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
