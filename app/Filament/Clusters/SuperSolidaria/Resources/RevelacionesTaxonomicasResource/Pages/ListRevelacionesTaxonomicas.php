<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\RevelacionesTaxonomicasResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\RevelacionesTaxonomicasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRevelacionesTaxonomicas extends ListRecords
{
    protected static string $resource = RevelacionesTaxonomicasResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
