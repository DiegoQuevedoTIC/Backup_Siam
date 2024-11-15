<?php

namespace App\Filament\Resources\PagoColectivoResource\Pages;

use App\Filament\Resources\PagoColectivoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPagoColectivo extends ViewRecord
{
    protected static string $resource = PagoColectivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
