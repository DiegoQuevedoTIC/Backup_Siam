<?php

namespace App\Filament\Resources\AsociadoResource\Pages;

use App\Filament\Resources\AsociadoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateAsociado extends CreateRecord
{
    protected static string $resource = AsociadoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Asociado Registrado OK.')
            ->body('El Asociado se ha creado de manera correcta');
    }
}
