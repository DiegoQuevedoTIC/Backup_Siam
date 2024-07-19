<?php

namespace App\Filament\Resources\NovedadTerceroResource\Pages;

use App\Filament\Resources\NovedadTerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditNovedadTercero extends EditRecord
{
    protected static string $resource = NovedadTerceroResource::class;

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
            ->title('Novedad del Tercero Actualizada')
            ->body('La Novedad delTercero se ha actualizado de manera correcta');
    }
}
