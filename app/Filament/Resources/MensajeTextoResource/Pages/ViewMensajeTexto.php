<?php

namespace App\Filament\Resources\MensajeTextoResource\Pages;

use App\Filament\Resources\MensajeTextoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMensajeTexto extends ViewRecord
{
    protected static string $resource = MensajeTextoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
