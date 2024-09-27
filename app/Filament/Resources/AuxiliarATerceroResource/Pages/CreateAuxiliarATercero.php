<?php

namespace App\Filament\Resources\AuxiliarATerceroResource\Pages;

use App\Filament\Resources\AuxiliarATerceroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use stdClass;

class CreateAuxiliarATercero extends CreateRecord
{
    protected static string $resource = AuxiliarATerceroResource::class;

    protected static string $view = 'custom.auxiliar.create';

    public $showPDF = false, $src, $cuentas_puc = [];

    public function __construct()
    {
        $this->cuentas_puc = DB::table('pucs')->select('id', 'puc', 'descripcion', 'grupo', 'puc_padre', 'naturaleza')->get()->toArray();
    }

    public function exportPDF()
    {

        if (empty($this->data['fecha_inicial']) || empty($this->data['fecha_final']) || empty($this->data['tercero_id'])) {
            Notification::make()
                ->title('Por favor llenar los campos obligatorios.')
                ->danger()
                ->send();

            return;
        }

        $data = $this->generateReport($this->data['fecha_inicial'], $this->data['fecha_final'], $this->data['tercero_id']);

        if (count($data) == 0) {
            Notification::make()
                ->title('No se encontraron movimientos para el tercero seleccionado.')
                ->warning()
                ->send();
            return;
        }

        $pdf = Pdf::loadView('pdf.auxiliar_tercero', $data);

        $this->src = 'data:application/pdf;base64,' . base64_encode($pdf->output());

        $this->showPDF = true;
    }

    public function generateReport($fecha_inicial, $fecha_final, $tercero_id, $cuenta_inicial = null, $cuenta_final = null): array
    {

        $movimientos = $this->buscarMovimientos($fecha_inicial, $fecha_final, $tercero_id);

        if (count($movimientos) == 0) {
            return [];
        }

        $tercero  = new stdClass();
        $tercero->tercero = $movimientos[0]->tercero;
        $tercero->tercero_nombre = $movimientos[0]->tercero_nombre;
        $tercero->primer_apellido = $movimientos[0]->primer_apellido;
        $tercero->segundo_apellido = $movimientos[0]->segundo_apellido;

        // Variable para agrupar cuentas por puc
        $movimientos_por_cuenta = [];

        // Inicializar un arreglo para almacenar saldos anteriores por cuenta PUC
        $saldos_anteriores = [];

        foreach ($movimientos as $key => $movimiento) {
            // Verificar si ya se ha calculado el saldo anterior para esta cuenta PUC
            if (!isset($saldos_anteriores[$movimiento->puc])) {
                // Si no se ha calculado, llamar a la función y almacenar el resultado
                $saldos_anteriores[$movimiento->puc] = buscarSaldoAnterior($fecha_inicial, $movimiento->puc);
            }

            // Asignar el saldo anterior desde el arreglo
            $movimiento->saldo_anterior = $saldos_anteriores[$movimiento->puc];

            // Calcular saldo nuevo
            if ($movimiento->naturaleza == 'C') {
                $movimiento->saldo_nuevo = $movimiento->saldo_anterior + $movimiento->credito - $movimiento->debito;
            } else {
                $movimiento->saldo_nuevo = $movimiento->saldo_anterior - $movimiento->debito + $movimiento->credito;
            }

            // Actualizar el saldo anterior para la siguiente iteración
            $saldos_anteriores[$movimiento->puc] = $movimiento->saldo_nuevo;

            // Agrupar por cuenta PUC
            $movimientos_por_cuenta[$movimiento->puc]['movimientos'][] = $movimiento;
            $movimientos_por_cuenta[$movimiento->puc]['descripcion'] = $movimiento->descripcion_linea; // Asumiendo que hay una descripción
        }

        // Preparar los datos para el PDF
        return [
            'titulo' => 'Auxiliar Tercero',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'auxiliar_tercero',
            'cuentas' => $movimientos_por_cuenta,
            'tercero' => $tercero,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    }

    public function buscarMovimientos($fecha_inicial, $fecha_final, $tercero_id): object
    {
        return DB::table('comprobantes as c')
            ->select('p.puc', 'p.naturaleza', 'tpd.sigla as documento', 't.tercero_id as tercero', 't.nombres as tercero_nombre', 't.primer_apellido', 't.segundo_apellido', 'cl.descripcion_linea', 'cl.debito', 'cl.credito', 'c.fecha_comprobante as fecha', 'c.n_documento')
            ->join('comprobante_lineas as cl', 'c.id', '=', 'cl.comprobante_id')
            ->join('terceros as t', 'cl.tercero_id', '=', 't.id')
            ->leftJoin('pucs as p', 'cl.pucs_id', '=', 'p.id')
            ->leftJoin('tipo_documento_contables as tpd', 'c.tipo_documento_contables_id', '=', 'tpd.id')
            ->where('t.id', $tercero_id)
            ->whereBetween('c.fecha_comprobante', [$fecha_inicial, $fecha_final])
            ->orderBy('c.fecha_comprobante')
            ->get();
    }
}

// Funcion para buscar el saldo anterior de la fecha inicial
function buscarSaldoAnterior($fecha_inicial, $puc): string
{
    // Convertir la fecha inicial a un objeto DateTime
    $fecha = new \DateTime($fecha_inicial);

    // Restar un día para obtener la fecha del día anterior
    $fecha->modify('-1 day');

    // Obtener año y mes de la nueva fecha
    $ano_inicial = $fecha->format('Y');
    $mes_inicial = $fecha->format('n');

    // Consultar el saldo anterior
    $cuenta = DB::table('saldo_pucs')
        ->where('amo', $ano_inicial)
        ->where('mes', $mes_inicial)
        ->where('puc', $puc)
        ->orderBy('id', 'DESC')
        ->first();

    return $cuenta->saldo ?? 0.00;
}
