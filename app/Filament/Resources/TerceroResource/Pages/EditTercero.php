<?php

namespace App\Filament\Resources\TerceroResource\Pages;

use App\Filament\Resources\TerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTercero extends EditRecord
{
    protected static string $resource = TerceroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),

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
            ->title('Tercero Actualizado')
            ->body('El Tercero se ha actualizado de manera correcta');
    }
}
