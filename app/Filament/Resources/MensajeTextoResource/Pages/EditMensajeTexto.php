<?php

namespace App\Filament\Resources\MensajeTextoResource\Pages;

use App\Filament\Resources\MensajeTextoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMensajeTexto extends EditRecord
{
    protected static string $resource = MensajeTextoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
