<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\RelacionInversionesResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\RelacionInversionesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRelacionInversiones extends ListRecords
{
    protected static string $resource = RelacionInversionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
