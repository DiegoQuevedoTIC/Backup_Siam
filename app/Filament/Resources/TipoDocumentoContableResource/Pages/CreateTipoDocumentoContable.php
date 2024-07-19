<?php

namespace App\Filament\Resources\TipoDocumentoContableResource\Pages;

use App\Filament\Resources\TipoDocumentoContableResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTipoDocumentoContable extends CreateRecord
{
    protected static string $resource = TipoDocumentoContableResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tipo de Documento Registrado OK.')
            ->body('El Tipo de Documento Contable se ha creado de manera correcta');
    }
}
