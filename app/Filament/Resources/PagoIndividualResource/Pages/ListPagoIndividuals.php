<?php

namespace App\Filament\Resources\PagoIndividualResource\Pages;

use App\Filament\Resources\PagoIndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPagoIndividuals extends ListRecords
{
    protected static string $resource = PagoIndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
