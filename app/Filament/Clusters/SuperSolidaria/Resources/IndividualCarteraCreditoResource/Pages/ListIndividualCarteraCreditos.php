<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\IndividualCarteraCreditoResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\IndividualCarteraCreditoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndividualCarteraCreditos extends ListRecords
{
    protected static string $resource = IndividualCarteraCreditoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
