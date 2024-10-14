<?php

namespace App\Filament\Resources\AuxiliarATerceroResource\Pages;

use App\Exports\AuxiliaresExport;
use App\Filament\Resources\AuxiliarATerceroResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use stdClass;

class CreateAuxiliarATercero extends CreateRecord
{
    protected static string $resource = AuxiliarATerceroResource::class;

    protected static string $view = 'custom.auxiliares.create';

    public function exportPDF()
    {

        $tipo = $this->data['tipo_auxiliar'];
        $fecha_inicial = $this->data['fecha_inicial'];
        $fecha_final = $this->data['fecha_final'];
        $tercero_id = $this->data['tercero_id'];

        switch ($tipo) {
            case '1':
                $data = $this->generateAuxiliarTercero($fecha_inicial, $fecha_final, $tercero_id);
                $name = 'auxiliares_terceros_' . now()->format('Y-m-d') . '.xlsx';
                break;
            case '2':
                $data = $this->generateAuxiliarCuentaDetalle($fecha_inicial, $fecha_final);
                $name = 'auxiliares_cuentas_' . now()->format('Y-m-d') . '.xlsx';
                break;
            case '3':
                $data = $this->generateAuxiliarCuentaDetalle($fecha_inicial, $fecha_final);
                $name = 'auxiliares_detalles_cuentas_' . now()->format('Y-m-d') . '.xlsx';
                break;
            case '4':
                $data = $this->generateAuxiliarTipoDocumento($fecha_inicial, $fecha_final);
                $name = 'auxiliares_tipo_doccumento_' . now()->format('Y-m-d') . '.xlsx';
                break;
            default:
                $data = [];
        }

        //dd($data);

        if (count($data) === 0) {
            Notification::make()
                ->title('Error al generar el reporte.')
                ->body('No se encontraron movimientos para los filtros seleccionados.')
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title('Reporte generado con exito.')
            ->body('El reporte sera descargado automaticamente.')
            ->success()
            ->send();

        return Excel::download(new AuxiliaresExport($data), $name);
    }


    public function generateAuxiliarTercero($fecha_inicial, $fecha_final, $tercero_id, $cuenta_inicial = null, $cuenta_final = null): array
    {

        $movimientos = buscarMovimientos($fecha_inicial, $fecha_final, $tercero_id);

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

    public function generateAuxiliarCuentas($fecha_inicial, $fecha_final, $cuenta_inicial = null, $cuenta_final = null): array
    {
        $movimientos = buscarMovimientosCuentas($fecha_inicial, $fecha_final);

        if (count($movimientos) == 0) {
            return [];
        }

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
            'titulo' => 'Auxiliar a Cuentas',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'auxiliar_cuentas',
            'cuentas' => $movimientos_por_cuenta,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    }

    public function generateAuxiliarCuentaDetalle($fecha_inicial, $fecha_final, $cuenta_inicial = null, $cuenta_final = null): array
    {
        $movimientos = buscarMovimientosCuentas($fecha_inicial, $fecha_final);

        if (count($movimientos) == 0) {
            return [];
        }

        // Variable para agrupar cuentas por puc
        $movimientos_por_cuenta = [];

        // Inicializar un arreglo para almacenar saldos anteriores por cuenta PUC
        $saldos_anteriores = [];

        // Obtener las cuentas padres, abuelos y bisabuelos
        $cuentas_padres = [];
        $cuentas_abuelos = [];
        $cuentas_bisabuelos = [];
        $cuentas_tatarabuelos = []; // Agregar arreglo para tatarabuelos

        foreach ($movimientos as $movimiento) {
            // Obtener cuenta padre
            if (!isset($cuentas_padres[$movimiento->puc_padre])) {
                $cuenta_padre = DB::table('pucs')->where('puc', $movimiento->puc_padre)->first();
                if ($cuenta_padre) {
                    $cuentas_padres[$movimiento->puc_padre] = $cuenta_padre;
                }
            }

            // Obtener cuenta abuelo
            if (!isset($cuentas_abuelos[$movimiento->puc_padre])) {
                if (isset($cuentas_padres[$movimiento->puc_padre])) {
                    $cuenta_abuelo = DB::table('pucs')->where('puc', $cuentas_padres[$movimiento->puc_padre]->puc_padre)->first();
                    if ($cuenta_abuelo) {
                        $cuentas_abuelos[$movimiento->puc_padre] = $cuenta_abuelo;
                    }
                }
            }

            // Obtener cuenta bisabuelo
            if (!isset($cuentas_bisabuelos[$movimiento->puc_padre])) {
                if (isset($cuentas_abuelos[$movimiento->puc_padre])) {
                    $cuenta_bisabuelo = DB::table('pucs')->where('puc', $cuentas_abuelos[$movimiento->puc_padre]->puc_padre)->first();
                    if ($cuenta_bisabuelo) {
                        $cuentas_bisabuelos[$movimiento->puc_padre] = $cuenta_bisabuelo;
                    }
                }
            }

            // Obtener cuenta tatarabuelo
            if (!isset($cuentas_tatarabuelos[$movimiento->puc_padre])) {
                if (isset($cuentas_bisabuelos[$movimiento->puc_padre])) {
                    $cuenta_tatarabuelo = DB::table('pucs')->where('puc', $cuentas_bisabuelos[$movimiento->puc_padre]->puc_padre)->first();
                    if ($cuenta_tatarabuelo) {
                        $cuentas_tatarabuelos[$movimiento->puc_padre] = $cuenta_tatarabuelo;
                    }
                }
            }
        }

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

            // Agregar la cuenta padre a la agrupación
            if (isset($cuentas_padres[$movimiento->puc_padre])) {
                $movimientos_por_cuenta[$movimiento->puc]['cuenta_padre'] = $cuentas_padres[$movimiento->puc_padre]->puc . ' ' . $cuentas_padres[$movimiento->puc_padre]->descripcion; // Asumiendo que hay una columna 'descripcion'
            }

            // Agregar la cuenta abuelo a la agrupación
            if (isset($cuentas_abuelos[$movimiento->puc_padre])) {
                $movimientos_por_cuenta[$movimiento->puc]['cuenta_abuelo'] = $cuentas_abuelos[$movimiento->puc_padre]->puc . ' ' . $cuentas_abuelos[$movimiento->puc_padre]->descripcion; // Asumiendo que hay una columna 'descripcion'
            }

            // Agregar la cuenta bisabuelo a la agrupación
            if (isset($cuentas_bisabuelos[$movimiento->puc_padre])) {
                $movimientos_por_cuenta[$movimiento->puc]['cuenta_bisabuelo'] = $cuentas_bisabuelos[$movimiento->puc_padre]->puc . ' ' . $cuentas_bisabuelos[$movimiento->puc_padre]->descripcion; // Asumiendo que hay una columna 'descripcion'
            }

            // Agregar la cuenta tatarabuelo a la agrupación
            if (isset($cuentas_tatarabuelos[$movimiento->puc_padre])) {
                $movimientos_por_cuenta[$movimiento->puc]['cuenta_tatarabuelo'] = $cuentas_tatarabuelos[$movimiento->puc_padre]->puc . ' ' . $cuentas_tatarabuelos[$movimiento->puc_padre]->descripcion; // Asumiendo que hay una columna 'descripcion'
            }
        }

        // Preparar los datos para el PDF
        return [
            'titulo' => 'Auxiliar a Cuentas Detalles',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'auxiliar_cuentas_detalles',
            'cuentas' => $movimientos_por_cuenta,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    }

    /* public function generateAuxiliarTipoDocumento($fecha_inicial, $fecha_final): array
    {
        $movimientos = buscarMovimientosCuentas($fecha_inicial, $fecha_final);

        if (count($movimientos) == 0) {
            return [];
        }

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
            'titulo' => 'Auxiliar a Cuentas',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'auxiliar_tipo_documento',
            'cuentas' => $movimientos_por_cuenta,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    } */

    public function generateAuxiliarTipoDocumento($fecha_inicial, $fecha_final): array
    {
        $movimientos = buscarMovimientosCuentas($fecha_inicial, $fecha_final);

        if (count($movimientos) == 0) {
            return [];
        }

        // Variable para agrupar movimientos por sigla
        $movimientos_por_sigla = [];

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

            // Agrupar por sigla
            $movimientos_por_sigla[$movimiento->documento]['movimientos'][] = $movimiento;
            $movimientos_por_sigla[$movimiento->documento]['descripcion'] = $movimiento->descripcion_linea; // Asumiendo que hay una descripción
        }

        // Ordenar los movimientos por sigla
        ksort($movimientos_por_sigla);

        // Preparar los datos para el PDF
        return [
            'titulo' => 'Auxiliar a Cuentas',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'auxiliar_tipo_documento',
            'cuentas' => $movimientos_por_sigla,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    }
}

// Buscar movimientos
function buscarMovimientos($fecha_inicial, $fecha_final, $tercero_id): object
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


// Buscar movimientos auxiliar a cuentas
function buscarMovimientosCuentas($fecha_inicial, $fecha_final): object
{
    return DB::table('comprobantes AS c')
        ->select([
            'p.puc',
            'p.naturaleza',
            'tpd.sigla AS documento',
            't.tercero_id AS tercero',
            'cl.descripcion_linea',
            'cl.debito',
            'cl.credito',
            'c.fecha_comprobante AS fecha',
            'c.n_documento',
            'p.puc_padre'
        ])
        ->join('comprobante_lineas AS cl', 'c.id', '=', 'cl.comprobante_id')
        ->leftJoin('terceros AS t', 'cl.tercero_id', '=', 't.id')
        ->leftJoin('pucs AS p', 'cl.pucs_id', '=', 'p.id')
        ->leftJoin('tipo_documento_contables AS tpd', 'c.tipo_documento_contables_id', '=', 'tpd.id')
        ->whereBetween('c.fecha_comprobante', [$fecha_inicial, $fecha_final])
        ->get();
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
