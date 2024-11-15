<?php

namespace App\Filament\Resources\CorreosMasivoResource\Pages;

use App\Filament\Resources\CorreosMasivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorreosMasivos extends ListRecords
{
    protected static string $resource = CorreosMasivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
