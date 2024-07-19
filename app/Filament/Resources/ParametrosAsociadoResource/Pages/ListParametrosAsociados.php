<?php

namespace App\Filament\Resources\ParametrosAsociadoResource\Pages;

use App\Filament\Resources\ParametrosAsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParametrosAsociados extends ListRecords
{
    protected static string $resource = ParametrosAsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
