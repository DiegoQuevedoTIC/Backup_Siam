<?php

namespace App\Filament\Resources\AuxiliarATerceroResource\Pages;

use App\Exports\AuxiliaresExport;
use App\Filament\Resources\AuxiliarATerceroResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class CreateAuxiliarATercero extends CreateRecord
{
    protected static string $resource = AuxiliarATerceroResource::class;

    protected static string $view = 'custom.auxiliares.create';

    public function exportPDF()
    {

        Notification::make()
            ->title('Reporte generado con exito.')
            ->body('El reporte sera descargado automaticamente.')
            ->success()
            ->send();

        $tipo = $this->data['tipo_auxiliar'];
        $fecha_inicial = $this->data['fecha_inicial'];
        $fecha_final = $this->data['fecha_final'];
        $tercero_id = $this->data['tercero_id'];

        $name = 'auxiliares_reporte_' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new AuxiliaresExport($tipo, $fecha_inicial, $fecha_final, $tercero_id ?? null), $name);
    }
}
