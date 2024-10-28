<?php

namespace App\Filament\Resources\ConsultaComprobanteResource\Pages;

use App\Exports\ConsultaComprobanteExport;
use App\Filament\Resources\ConsultaComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Livewire\WithPagination;


class CreateConsultaComprobante extends CreateRecord
{
    use WithPagination;

    protected static string $resource = ConsultaComprobanteResource::class;

    protected static string $view = 'custom.consultas.consulta-comprobante';

    public $showOne = false;
    public $dataOne;

    public $showTable = false;
    public $datatable;
    public function generateReport()
    {
        try {
            $tipo_comprobante = $this->data['tipo_documento'];
            $nro_comprobante = $this->data['n_documento'];

            if (!$tipo_comprobante) {
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
                ->when($nro_comprobante, function ($query) use ($nro_comprobante) {
                    return $query->orWhere('c.n_documento', $nro_comprobante);
                })
                ->groupBy('c.fecha_comprobante', 'c.n_documento', 'c.descripcion_comprobante', 'c.id')
                ->orderBy('c.fecha_comprobante')
                ->get();

            /* if ($comprobante == 0) {
                Notification::make()
                    ->title('No se encontro comprobante con el tipo de documento seleccionado')
                    ->danger()
                    ->send();

                return false;
            }

            if (count($comprobante) === 1) {
                $this->showOne = true;
                $this->dataOne = $comprobante;
            } */


            if ($comprobante) {
                $this->showTable = true;
                $this->datatable = $comprobante;
            }
        } catch (\Throwable $th) {
            //throw $th;
            dd('Error: ' . $th->getMessage());

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
