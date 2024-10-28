<?php

namespace App\Filament\Resources\SaldoConciliadoResource\Pages;

use App\Filament\Resources\SaldoConciliadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaldoConciliados extends ListRecords
{
    protected static string $resource = SaldoConciliadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Generar Revision'),
        ];
    }
}
