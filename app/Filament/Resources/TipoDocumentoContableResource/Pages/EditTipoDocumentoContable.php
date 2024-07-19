<?php

namespace App\Filament\Resources\TipoDocumentoContableResource\Pages;

use App\Filament\Resources\TipoDocumentoContableResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTipoDocumentoContable extends EditRecord
{
    protected static string $resource = TipoDocumentoContableResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tipo de Documento Actualizado')
            ->body('El tipo de documento  se ha actualizado de manera correcta');
    }
}
