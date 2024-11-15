<?php

namespace App\Filament\Resources\MensajeTextoResource\Pages;

use App\Filament\Resources\MensajeTextoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMensajeTextos extends ListRecords
{
    protected static string $resource = MensajeTextoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
