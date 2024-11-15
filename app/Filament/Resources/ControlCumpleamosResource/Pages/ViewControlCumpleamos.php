<?php

namespace App\Filament\Resources\ControlCumpleamosResource\Pages;

use App\Filament\Resources\ControlCumpleamosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewControlCumpleamos extends ViewRecord
{
    protected static string $resource = ControlCumpleamosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
