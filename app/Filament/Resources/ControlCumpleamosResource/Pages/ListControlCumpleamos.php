<?php

namespace App\Filament\Resources\ControlCumpleamosResource\Pages;

use App\Filament\Resources\ControlCumpleamosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListControlCumpleamos extends ListRecords
{
    protected static string $resource = ControlCumpleamosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
