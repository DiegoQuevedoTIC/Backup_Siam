<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\IndividualCaptacionesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\IndividualCaptacionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndividualCaptaciones extends ListRecords
{
    protected static string $resource = IndividualCaptacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
