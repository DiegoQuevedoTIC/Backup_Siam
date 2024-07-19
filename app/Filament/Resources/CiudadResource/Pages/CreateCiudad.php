<?php

namespace App\Filament\Resources\CiudadResource\Pages;

use App\Filament\Resources\CiudadResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCiudad extends CreateRecord
{
    protected static string $resource = CiudadResource::class;




    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ciudad Registrada OK.')
            ->body('La Ciudad se ha creado de manera correcta');
    }
}
