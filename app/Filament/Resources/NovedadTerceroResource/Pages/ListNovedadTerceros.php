<?php

namespace App\Filament\Resources\NovedadTerceroResource\Pages;

use App\Filament\Resources\NovedadTerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNovedadTerceros extends ListRecords
{
    protected static string $resource = NovedadTerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('+ Nueva Novedad de Tercero'),
        ];
    }
}
