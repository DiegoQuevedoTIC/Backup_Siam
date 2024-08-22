<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class CreateCierreMensual extends CreateRecord
{
    protected static string $resource = CierreMensualResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Convertir la fecha a un timestamp
        $timestamp = strtotime($data['fecha_cierre']);

        // Obtener el número del día del mes
        $fecha_recibida = date('m', $timestamp);

        // Validamos que la fecha de cierre sea posterior a la recibida
        if ($data['mes_cierre'] < $fecha_recibida) {

            $data['fecha_cierre'] = Carbon::createFromFormat('Y-m-d', $data['fecha_cierre'])->format('Y-m-d');

            return $data;
        } else {
            Notification::make()
                ->title('Por favor coloca un fecha valida para realizar el proceso')
                ->danger()
                ->send();

            $this->halt();
            return [];
        }
    }

    protected function afterCreate(): void
    {
        DB::statement('CALL calcular_saldo_mes(?, ?)', [$this->getRecord()->fecha_cierre, $this->getRecord()->id]);
    }
}
