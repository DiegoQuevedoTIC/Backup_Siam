<?php

namespace App\Filament\Resources\PucResource\Pages;

use App\Filament\Resources\PucResource;
use App\Models\Puc;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditPuc extends EditRecord
{
    protected static string $resource = PucResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Cuenta PUC Actualizada')
            ->body('La Cuenta del PUC se ha actualizado de manera correcta');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        
        if (!is_null($data['puc_padre'])) {
            $puc_id = Puc::where('puc', '=', $data['puc_padre'])->get()->toArray();
            $data['pucs_id'] = $puc_id[0]['id'];
            return $data;
        }
        else
        {
            $data['puc_padre'] = '';
            return $data;
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        //dd($data);
        if(!$data['puc_padre'] === '')
        {
            $puc_id = Puc::where('puc', '=', $data['puc_padre'])->get()->toArray();
            $data['puc_padre'] = $puc_id['id'];
            return $data;
        }
        else{
            return $data;
        }
    }
}
