<?php

namespace App\Filament\Resources\CiudadResource\Pages;

use App\Filament\Resources\CiudadResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCiudad extends EditRecord
{
    protected static string $resource = CiudadResource::class;

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
            ->title('Ciudad Actualizada')
            ->body('La Ciudad se ha actualizado de manera correcta');
    }
}
