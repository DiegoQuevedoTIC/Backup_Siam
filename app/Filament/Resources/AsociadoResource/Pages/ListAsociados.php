<?php

namespace App\Filament\Resources\AsociadoResource\Pages;

use App\Filament\Resources\AsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsociados extends ListRecords
{
    protected static string $resource = AsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nuevo Asociado'),
        ];
    }
}
