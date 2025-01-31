<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\EvaluacionRiesgoLiquidezResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\EvaluacionRiesgoLiquidezResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluacionRiesgoLiquidezs extends ListRecords
{
    protected static string $resource = EvaluacionRiesgoLiquidezResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
