<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\FondoLiquidezResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\FondoLiquidezResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFondoLiquidezs extends ListRecords
{
    protected static string $resource = FondoLiquidezResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
