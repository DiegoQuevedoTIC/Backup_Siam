<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use Carbon\Carbon;
use DateTime;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreateCierreMensual extends CreateRecord
{
    protected static string $resource = CierreMensualResource::class;

    public $ano_actual, $rango_fechas;

    public function __construct()
    {
        // Obtener el año actual
        $this->ano_actual = date('Y');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Convertir la fecha a un timestamp
        $timestamp = strtotime($data['fecha_cierre']);

        // Obtener el número del día del mes
        $fecha_recibida = date('m', $timestamp);

        $this->rango_fechas = $this->obtenerRangoFecha($data['mes_cierre']);

        // Validamos que la fecha de cierre sea posterior a la recibida
        if ($data['mes_cierre'] < $fecha_recibida) {

            $data['fecha_cierre'] = Carbon::createFromFormat('Y-m-d', $data['fecha_cierre'])->format('Y-m-d');

            // Validamos que el mes de cierre no se repita
            $validator = DB::table('cierre_mensuales')->where('mes_cierre', $data['mes_cierre'])->first();

            if ($validator) {
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
        DB::statement('CALL cierre_mensual(CAST(? AS DATE), CAST(? AS DATE), CAST(? AS INT));', [
            $this->rango_fechas['primer_dia'],
            $this->rango_fechas['ultimo_dia'],
            $this->getRecord()->id
        ]);
    }



    protected function obtenerRangoFecha($mes_cierre)
    {
        // Obtener el año actual
        $ano = (int) $this->ano_actual;
        $mes_cierre = (int) $mes_cierre;

        // Asegurarse de que el mes_cierre sea un número válido
        if ($mes_cierre < 1 || $mes_cierre > 12) {
            throw new InvalidArgumentException("El mes debe estar entre 1 y 12.");
        }

        if ($mes_cierre === 12) {
            $ano = $ano - 1;
        }

        // Obtener el primer día del mes
        $primerDia = new DateTime("$ano-$mes_cierre-01");

        // Obtener el último día del mes
        $ultimoDia = new DateTime("$ano-$mes_cierre-01");
        $ultimoDia->modify('last day of this month');

        return [
            'primer_dia' => $primerDia->format('Y-m-d'),
            'ultimo_dia' => $ultimoDia->format('Y-m-d')
        ];
    }
}
