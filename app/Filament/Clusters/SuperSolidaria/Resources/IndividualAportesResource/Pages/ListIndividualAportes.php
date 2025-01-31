<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\IndividualAportesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\IndividualAportesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndividualAportes extends ListRecords
{
    protected static string $resource = IndividualAportesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
