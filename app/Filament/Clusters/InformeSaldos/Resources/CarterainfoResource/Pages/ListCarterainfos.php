<?php

namespace App\Filament\Clusters\InformeSaldos\Resources\CarterainfoResource\Pages;

use App\Filament\Clusters\InformeSaldos\Resources\CarterainfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarterainfos extends ListRecords
{
    protected static string $resource = CarterainfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
