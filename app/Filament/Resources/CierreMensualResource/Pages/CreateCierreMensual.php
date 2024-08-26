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

    public $ano_actual;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Convertir la fecha a un timestamp
        $timestamp = strtotime($data['fecha_cierre']);

        // Obtener el número del día del mes
        $fecha_recibida = date('m', $timestamp);

        // Obtener el año actual
        $this->ano_actual = date('Y', $timestamp);

        // Validamos que la fecha de cierre sea posterior a la recibida
        if ($data['mes_cierre'] < $fecha_recibida) {

            $data['fecha_cierre'] = Carbon::createFromFormat('Y-m-d', $data['fecha_cierre'])->format('Y-m-d');

            // Validamos que el mes de cierre no se repita
            $validator = DB::table('cierre_mensuales')->where('mes_cierre', $data['mes_cierre'])->first();

            if($validator){
                Notification::make()
                    ->title('El mes de cierre ya ha sido realizado')
                    ->danger()
                    ->send();

                $this->halt();
                return [];
            }

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
        DB::statement('CALL calcular_saldo_mes(?, ?, ?);', [$this->getRecord()->mes_cierre, $this->ano_actual, $this->getRecord()->id]);
    }
}
