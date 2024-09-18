<?php

namespace App\Exports;

use DateTime;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class BalancesExport implements FromView
{

    public $data, $fecha_inicial, $fecha_final;

    public function __construct($fecha_inicial, $fecha_final)
    {
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
    }

    public function view(): View
    {
        $fecha_inicial = $this->fecha_inicial;
        $fecha_final = $this->fecha_final;

        // Almacenar todas las cuentas PUC
        $cuentas_puc = DB::table('pucs')->select('id', 'puc', 'descripcion', 'grupo', 'puc_padre', 'naturaleza')->get()->toArray();

        // Crear un array asociativo para las cuentas PUC
        $pucs_normalizados = [];
        foreach ($cuentas_puc as $puc) {
            $pucs_normalizados[trim(strtolower($puc->puc))] = $puc->id;
        }

        // Obtener los movimientos y los saldos anteriores en una sola consulta
        $movimientos = buscarMovimientos($fecha_inicial, $fecha_final);

        // Generar el array de movimientos por cuenta
        $movimientos_por_cuenta = [];
        foreach ($cuentas_puc as $puc) {
            $saldo_anterior = buscarSaldoAnterior($fecha_inicial, $puc->puc);

            // Filtrar los movimientos de la cuenta actual
            $movimiento = $movimientos->firstWhere('puc', $puc->puc);

            // Inicializar el registro de la cuenta en el array
            $movimientos_por_cuenta[$puc->id] = [
                'puc' => $puc->puc,
                'descripcion' => $puc->descripcion,
                'debitos' => $movimiento->debitos ?? 0,
                'creditos' => $movimiento->creditos ?? 0,
                'saldo_anterior' => $saldo_anterior,
                'puc_padre' => $puc->puc_padre,
                'naturaleza' => $puc->naturaleza
            ];

            // Llamar a la función para sumar movimientos de cuentas hijas a cuentas padres
            sumarMovimientosPadres($puc->id, $movimientos_por_cuenta, $pucs_normalizados);
        }

        // Sumar los movimientos de las cuentas hijas a las cuentas padres
        foreach ($movimientos_por_cuenta as $key => $puc) {
            // Ajustar la lógica según la naturaleza de la cuenta
            if ($puc['naturaleza'] == 'C') { // Cuenta de crédito
                $debitos = $puc['debitos'] ?? 0; // Acceso correcto a propiedades
                $creditos = $puc['creditos'] ?? 0; // Acceso correcto a propiedades
                $saldo_nuevo = $puc['saldo_anterior'] + $creditos - $debitos; // Créditos positivos, débitos negativos
            } else { // Cuenta de débito
                $debitos = $puc['debitos'] ?? 0; // Acceso correcto a propiedades
                $creditos = $puc['creditos'] ?? 0; // Acceso correcto a propiedades
                $saldo_nuevo = $puc['saldo_anterior'] - $creditos + $debitos; // Créditos negativos, débitos positivos
            }

            // Actualizar el saldo nuevo en el array
            $movimientos_por_cuenta[$key]['saldo_nuevo'] = $saldo_nuevo;
        }

        // Filtrar resultados para incluir solo cuentas con movimientos
        $resultados = array_filter($movimientos_por_cuenta, function ($mov) {
            return $mov['debitos'] > 0 || $mov['creditos'] > 0 || $mov['saldo_anterior'] > 0;
        });

        // Totalizaciones
        $total_saldo_anteriores = array_sum(array_column($resultados, 'saldo_anterior'));
        $total_debitos = array_sum(array_column($resultados, 'debitos'));
        $total_creditos = array_sum(array_column($resultados, 'creditos'));
        $total_saldo_nuevo = array_sum(array_column($resultados, 'saldo_nuevo'));

        // Preparar los datos para el PDF
        $this->data = [
            'titulo' => 'Balance General',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'nit' => '8.000.903.753',
            'tipo_balance' => 'balance_general',
            'cuentas' => array_values($resultados),
            'total_saldo_anteriores' => $total_saldo_anteriores,
            'total_debitos' => $total_debitos,
            'total_creditos' => $total_creditos,
            'total_saldo_nuevo' => $total_saldo_nuevo,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
        ];

        return view('excel.balance', [
            'data' => $this->data
        ]);
    }
}


// Función recursiva para sumar movimientos de cuentas hijas a cuentas padres
function sumarMovimientosPadres($puc_id, &$movimientos_por_cuenta, $pucs_normalizados): void
{
    if (isset($movimientos_por_cuenta[$puc_id])) {
        $puc = $movimientos_por_cuenta[$puc_id];
        if (!empty($puc['puc_padre'])) {
            $puc_padre_normalizado = trim(strtolower($puc['puc_padre']));
            $padre_id = $pucs_normalizados[$puc_padre_normalizado] ?? false;

            if ($padre_id !== false) {
                // Asegurarse de que el padre tenga un array inicializado
                if (!isset($movimientos_por_cuenta[$padre_id])) {
                    $movimientos_por_cuenta[$padre_id] = [
                        'debitos' => 0,
                        'creditos' => 0,
                        'saldo_anterior' => 0,
                    ];
                }

                // Sumar los movimientos de la cuenta hija al padre
                $movimientos_por_cuenta[$padre_id]['debitos'] += $puc['debitos'];
                $movimientos_por_cuenta[$padre_id]['creditos'] += $puc['creditos'];

                // Llamar recursivamente para el padre
                sumarMovimientosPadres($padre_id, $movimientos_por_cuenta, $pucs_normalizados);
            }
        }
    }
}

// Funcion para buscar el saldo anterior de la fecha inicial
function buscarSaldoAnterior($fecha_inicial, $puc): string
{
    // Convertir la fecha inicial a un objeto DateTime
    $fecha = new DateTime($fecha_inicial);

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

// Funcion para buscar movimientos de las cuentas puc
function buscarMovimientos($fecha_inicial, $fecha_final): object
{
    return DB::table('comprobantes as c')
        ->join('comprobante_lineas as cl', 'cl.comprobante_id', 'c.id')
        ->leftJoin('pucs as p', 'cl.pucs_id', 'p.id')
        ->whereBetween('c.fecha_comprobante', [$fecha_inicial, $fecha_final])
        ->select(
            'p.puc',
            DB::raw('SUM(CASE WHEN cl.debito > 0 THEN cl.debito ELSE 0.00 END) AS debitos'),
            DB::raw('SUM(CASE WHEN cl.credito > 0 THEN cl.credito ELSE 0.00 END) AS creditos'),
            'p.naturaleza'
        )
        ->groupBy('p.puc', 'p.naturaleza')
        ->get();
}

// Funcion para validar si el mes anterior a la fecha inicial esta cerrado
function validarCierreMes($fecha_inicial): bool
{
    $fecha = new DateTime($fecha_inicial);
    $fecha->modify('-1 month');
    $ano_mes_anterior = $fecha->format('m');

    // Consultar si el mes anterior a la fecha inicial está cerrado
    $cierre = DB::table('saldo_pucs')
        ->where('mes', $ano_mes_anterior)
        ->first();

    return $cierre ? false : true;
}
