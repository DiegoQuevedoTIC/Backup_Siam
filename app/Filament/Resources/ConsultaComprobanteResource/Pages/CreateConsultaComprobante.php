<?php

namespace App\Filament\Resources\ConsultaComprobanteResource\Pages;

use App\Exports\ConsultaComprobanteExport;
use App\Filament\Resources\ConsultaComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class CreateConsultaComprobante extends CreateRecord
{
    protected static string $resource = ConsultaComprobanteResource::class;
    protected static string $view = 'custom.consultas.consulta-comprobante';

    public function generateReport()
    {
        try {
            $tipo_comprobante = $this->data['tipo_documento'];
            $nro_comprobante = $this->data['n_documento'];

            if (!$tipo_comprobante || !$nro_comprobante) {
                Notification::make()
                    ->title('Por favor los campos son obligatorios')
                    ->danger()
                    ->send();

                return false;
            }

            $comprobante = DB::table('comprobantes AS c')
                ->select(
                    'c.fecha_comprobante',
                    'c.n_documento',
                    'c.descripcion_comprobante',
                    DB::raw('SUM(cl.debito) AS total_debito'),
                    DB::raw('SUM(cl.credito) AS total_credito')
                )
                ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
                ->where('c.tipo_documento_contables_id', $tipo_comprobante)
                ->where('c.n_documento', $nro_comprobante)
                ->groupBy('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante', 'c.id')
                ->orderBy('c.fecha_comprobante')
                ->get();

            if (count($comprobante) == 0) {
                Notification::make()
                    ->title('El comprobante no existe')
                    ->danger()
                    ->send();

                return false;
            }

            $nameFile = 'consulta_' . now() . '.xlsx';
            return Excel::download(new ConsultaComprobanteExport($comprobante), $nameFile);
        } catch (\Throwable $th) {
            //throw $th;
            //dd('Error: '. $th->getMessage());

            Notification::make()
                ->title('Ocurrio un error!.')
                ->icon('heroicon-o-exclamation-circle')
                ->body('Ha ocurrido un error al generar el reporte')
                ->danger()
                ->send();

            return false;
        }
    }
}
