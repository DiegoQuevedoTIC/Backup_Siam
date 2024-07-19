<?php

namespace App\Filament\Resources\SubcentroResource\Pages;

use App\Filament\Resources\SubcentroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSubcentro extends CreateRecord
{
    protected static string $resource = SubcentroResource::class;



    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Subcentro Registrada OK.')
            ->body('El Subcentro de Costo se ha creado de manera correcta');
    }



}
