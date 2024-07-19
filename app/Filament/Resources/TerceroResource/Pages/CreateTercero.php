<?php

namespace App\Filament\Resources\TerceroResource\Pages;

use App\Filament\Resources\TerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateTercero extends CreateRecord
{
    protected static string $resource = TerceroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Tercero Registrado OK.')
            ->body('El Tercero se ha creado de manera correcta');
    }
}
