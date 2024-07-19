<?php

namespace App\Filament\Resources\NovedadTerceroResource\Pages;

use App\Filament\Resources\NovedadTerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateNovedadTercero extends CreateRecord
{
    protected static string $resource = NovedadTerceroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Novedad Registrada OK.')
            ->body('La Novedad para el Tercero se ha creado de manera correcta');
    }
}
