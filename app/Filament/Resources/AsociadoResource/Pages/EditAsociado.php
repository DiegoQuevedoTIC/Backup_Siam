<?php

namespace App\Filament\Resources\AsociadoResource\Pages;

use App\Filament\Resources\AsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditAsociado extends EditRecord
{
    protected static string $resource = AsociadoResource::class;

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
            ->title('Asociado Actualizado')
            ->body('El Asociado suario se ha actualizado de manera correcta');
    }
}




