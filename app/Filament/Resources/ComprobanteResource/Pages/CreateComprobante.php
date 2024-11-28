<?php

namespace App\Filament\Resources\ComprobanteResource\Pages;

use App\Filament\Resources\ComprobanteResource;
use App\Filament\Resources\ComprobanteResource\Widgets\PlantillaComprobanteOverview;
use Filament\Notifications\Livewire\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateComprobante extends CreateRecord
{
    protected static string $resource = ComprobanteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (array_key_exists('usar_plantilla', $data)) {
            unset($data['usar_plantilla']);
            unset($data['plantilla']);
        }
        if (!array_key_exists('fecha_comprobante', $data)) {
            $data['fecha_comprobante'] = date('Y-m-d');
            if ($data['estado'] == false) $data['estado'] = 'Activo';
            else $data['estado'] = 'Inactivo';
            return $data;
        } else {
            return $data;
        }
    }

    protected function beforeCreate(): void
    {
        $data = $this->data;

        $comprobante = DB::table('comprobantes')->where('n_documento', $data['n_documento'])->first();

        if ($comprobante) {
            Notification::make()
                ->title('El número de documento ya existe')
                ->danger()
                ->send();

            $this->halt();
        }

        // Validamos que la fecha del comprobante ya no este cerrada
        $validator = DB::table('cierre_mensuales')->where('mes_cierre', date('m', strtotime($data['fecha_comprobante'])))->first();

        if ($validator) {
            Notification::make()
                ->title('El mes de cierre no ha sido realizado')
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
}
