<?php

namespace App\Filament\Resources\DesbalanceResource\Pages;

use App\Exports\ComprobanteMovimientoExport;
use App\Exports\DesbalanceExport;
use App\Exports\partidaTerceroExport;
use App\Filament\Resources\DesbalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class CreateDesbalance extends CreateRecord
{
    protected static string $resource = DesbalanceResource::class;
    protected static string $view = 'custom.consultas.desbalance';

    public function generateReport()
    {
        $tipo_revision = $this->data['tipo_revision'];

        switch ($tipo_revision) {
            case '1':
                // Debito = Credito
                return $this->desbalance();
                break;
            case '2':
                // Cuentas de movimiento
                return $this->cuentasMovimiento();
                break;
            case '3':
                // Partidas con tercero
                return $this->partidasConTercero();
                break;
        }
    }


    function desbalance()
    {
        try {
            // Ejecutar la consulta
            $desbalanceados = DB::table('comprobantes AS c')
                ->select(
                    'c.fecha_comprobante',
                    'c.n_documento',
                    'c.descripcion_comprobante',
                    DB::raw('SUM(cl.debito) AS total_debito'),
                    DB::raw('SUM(cl.credito) AS total_credito')
                )
                ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
                ->groupBy('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante', 'c.id')
                ->havingRaw('SUM(cl.debito) <> SUM(cl.credito)')
                ->orderBy('c.fecha_comprobante')
                ->get();

            if (count($desbalanceados) == 0) {
                Notification::make()
                    ->title('Reporte generado con exito.')
                    ->body('No se encontraron comprobantes desbalanceados.')
                    ->success()
                    ->send();

                return;
            }

            $nameFile = 'consulta_' . now() . '.xlsx';
            return Excel::download(new DesbalanceExport($desbalanceados), $nameFile);
        } catch (\Exception $e) {
            // Capturar y mostrar cualquier error
            //dd('Error: ' . $e->getMessage());

            Notification::make()
                ->title('Ocurrio un error!.')
                ->body('por favor intentalo mas tarde.')
                ->danger()
                ->send();
        }
    }

    function cuentasMovimiento()
    {
        try {
            // Ejecutar la consulta
            $query = DB::table('comprobantes AS c')
                ->select('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante')
                ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
                ->join('pucs AS p', 'cl.pucs_id', '=', 'p.id')
                ->where('p.movimiento', false)
                ->get();

            if (count($query) == 0) {
                Notification::make()
                    ->title('Reporte generado con exito.')
                    ->body('No se encontraron comprobantes sin validar.')
                    ->success()
                    ->send();
                return;
            }

            $nameFile = 'consulta_' . now() . '.xlsx';
            return Excel::download(new ComprobanteMovimientoExport($query), $nameFile);
        } catch (\Exception $e) {
            // Capturar y mostrar cualquier error
            //dd('Error: ' . $e->getMessage());

            Notification::make()
                ->title('Ocurrio un error!.')
                ->body('por favor intentalo mas tarde.')
                ->danger()
                ->send();
        }
    }

    function partidasConTercero()
    {
        try {
            // Ejecutar la consulta
            $query = DB::table('comprobantes AS c')
                ->select('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante', 'cl.linea', 'td.sigla')
                ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
                ->join('pucs AS p', 'cl.pucs_id', '=', 'p.id')
                ->join('tipo_documento_contables as td', 'c.tipo_documento_contables_id', 'td.id')
                ->where('p.tercero', true)
                ->whereNull('cl.tercero_id')
                ->orderBy('c.fecha_comprobante')
                ->orderBy('c.n_documento')
                ->orderBy('cl.linea')
                ->get();

            if (count($query) == 0) {
                Notification::make()
                    ->title('Reporte generado con exito.')
                    ->body('No se encontraron comprobantes sin validar.')
                    ->success()
                    ->send();

                return;
            }

            $nameFile = 'consulta_' . now() . '.xlsx';
            return Excel::download(new partidaTerceroExport($query), $nameFile);
        } catch (\Exception $e) {
            // Capturar y mostrar cualquier error
            dd('Error: ' . $e->getMessage());

            Notification::make()
                ->title('Ocurrio un error!.')
                ->body('por favor intentalo mas tarde.')
                ->danger()
                ->send();
        }
    }
}
