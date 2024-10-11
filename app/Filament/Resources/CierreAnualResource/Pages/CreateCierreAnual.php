<?php

namespace App\Filament\Resources\CierreAnualResource\Pages;

use App\Filament\Resources\CierreAnualResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CreateCierreAnual extends CreateRecord
{
    protected static string $resource = CierreAnualResource::class;

    public $ano_actual;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        // Convertir la fecha a un timestamp
        $timestamp = strtotime($data['fecha_cierre']);

        // Validar si la fecha del año de cierre es menor al año actual
        if (date('Y', $timestamp) >= date('Y')) {
            Notification::make()
                ->title('No se puede realizar el cierre anual para un año futuro')
                ->danger()
                ->send();

            $this->halt();
        }

        // Validamos que el mes de cierre no se repita
        $validator = DB::table('comprobantes')->where('tipo_documento_contables_id', 14)->whereYear('fecha_comprobante', $data['ano_cierre'])->first();

        if ($validator) {
            Notification::make()
                ->title('El cierre anual ya ha sido realizado')
                ->danger()
                ->send();

            $this->halt();
            return [];
        }

        // Validar que los meses de noviembre y diciembre estén cerrados
        $validator2 = DB::table('cierre_mensuales')
            ->whereYear('fecha_cierre', $data['ano_cierre'])
            ->whereIn('mes_cierre', [11, 12])
            ->limit(2)
            ->get();


        if (!$validator2) {
            Notification::make()
                ->title('Algunos meses del año proporcionado no han sido cerrados')
                ->danger()
                ->send();

            $this->halt();
        }


        $this->ano_actual = $data['ano_cierre'];

        return $data;
    }


    protected function afterCreate(): void
    {
        DB::statement('CALL cierre_anual(?, ?);', [$this->ano_actual, $this->getRecord()->id]);
    }
}
