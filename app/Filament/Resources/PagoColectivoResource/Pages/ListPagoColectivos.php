<?php

namespace App\Filament\Resources\PagoColectivoResource\Pages;

use App\Filament\Resources\PagoColectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagoColectivos extends ListRecords
{
    protected static string $resource = PagoColectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
