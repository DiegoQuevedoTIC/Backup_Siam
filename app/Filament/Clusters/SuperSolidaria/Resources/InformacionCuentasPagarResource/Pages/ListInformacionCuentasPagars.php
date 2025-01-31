<?php

namespace App\Filament\Clusters\SuperSolidaria\Resources\InformacionCuentasPagarResource\Pages;

use App\Filament\Clusters\SuperSolidaria\Resources\InformacionCuentasPagarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInformacionCuentasPagars extends ListRecords
{
    protected static string $resource = InformacionCuentasPagarResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
