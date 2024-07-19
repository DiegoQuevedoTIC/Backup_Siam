<?php

namespace App\Filament\Resources\PucResource\Pages;

use App\Filament\Resources\PucResource;
use App\Models\Puc;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePuc extends CreateRecord
{
    protected static string $resource = PucResource::class;



    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cuenta PUC Registrada OK.')
            ->body('La Cuenta PUC se ha creado de manera correcta');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!is_null($data['puc_padre'])) {
            $puc_id = Puc::where('puc', '=', $data['puc_padre'])->get()->toArray();
            $data['pucs_id'] = $puc_id[0]['id'];
            return $data;
        }
        else
        {
            $data['puc_padre'] = '';
            $data['pucs_id'] = NULL;
            return $data;
        }
    }


}
