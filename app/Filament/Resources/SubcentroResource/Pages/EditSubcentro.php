<?php

namespace App\Filament\Resources\SubcentroResource\Pages;

use App\Filament\Resources\SubcentroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditSubcentro extends EditRecord
{
    protected static string $resource = SubcentroResource::class;

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
            ->title('Subcentro Actualizado')
            ->body('El Subcentro de Costo se ha actualizado de manera correcta');
    }

}
