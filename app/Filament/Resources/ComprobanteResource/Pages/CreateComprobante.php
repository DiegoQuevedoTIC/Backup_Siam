<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use App\Filament\Resources\ComprobanteResource\Widgets\PlantillaComprobanteOverview;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateComprobante extends CreateRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(array_key_exists('usar_plantilla', $data)){
            unset($data['usar_plantilla']);
            unset($data['plantilla']);
        }
        if (!array_key_exists('fecha_comprobante', $data)) {
            $data['fecha_comprobante'] = date('Y-m-d');
            return $data;
        } else {
            return $data;
        }
    }

    protected function beforeCreate(): void
    {
        $data = $this->data;
        $credito = array();
        $debito = array();
        foreach ($data['detalle'] as $key => $value) {
            if ($value['debito'] == '') {
                $credito[] = floatval($value['credito']);
            } else {
                $debito[] = floatval($value['debito']);
            }
        }

        if ((array_sum($credito) - array_sum($debito)) != 0.0) {
            Notification::make()
            ->title('No puede guardar un comprobante desbalanceado')
            ->danger()
            ->send();
            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
