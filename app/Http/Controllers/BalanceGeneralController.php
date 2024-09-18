<?php

namespace App\Http\Controllers;

use App\Exports\BalancesExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class BalanceGeneralController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    function generarBalanceHorizontal(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial; //$request->fecha_inicial; // '2024-01-01'
            $fecha_final = $request->fecha_final; //$request->fecha_final; // '2024-02-01'

            // permitir rango de fecha solo maximo de 3 meses
            $fecha_final = date('Y-m-d', strtotime($fecha_final . '+ 3 months'));

            // validar que la fecha inicial sea menor que la fecha final
            if ($fecha_inicial > $fecha_final) {
                return response()->json(['status' => 400, 'message' => 'La fecha inicial no puede ser mayor que la fecha final'], 400);
            }

            // Obtener año de la fecha incial
            $ano_inicial = date('Y', strtotime($fecha_inicial));

            // Obtener año de la fecha final
            $ano_final = date('Y', strtotime($fecha_final));


            /* // Validar que el rango de fecha incial y final solo tengan como maximo de 3 meses
            $fecha_inicial_dt = new DateTime($fecha_inicial);
            $fecha_final_dt = new DateTime($fecha_final);

            // Calcular la diferencia en meses
            $diferencia = $fecha_inicial_dt->diff($fecha_final_dt);
            $meses_diferencia = ($diferencia->y * 12) + $diferencia->m;

            // Validar que la diferencia no exceda 3 meses
            if ($meses_diferencia > 6) {
                return response()->json(['status' => 400, 'message' => 'El rango de fechas no puede ser mayor a 3 meses.'], 400);
            } */

            $cuentas = DB::table('saldo_pucs as sp')
                ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
                ->selectRaw('sp.puc, ps.descripcion,
                    SUM(CASE WHEN sp.amo::integer = ? THEN sp.saldo ELSE 0.00 END) AS "primer_rango",
                    SUM(CASE WHEN sp.amo::integer = ? THEN sp.saldo ELSE 0.00 END) AS "segundo_rango"', [$ano_inicial, $ano_final])
                ->whereIn('sp.amo', [$ano_inicial, $ano_final])
                ->groupBy('sp.puc', 'ps.descripcion')
                ->orderBy('sp.puc')
                ->get();


            $data = [
                'titulo' => 'Balance Horizontal',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'tipo_balance' => 'balance_horizontal',
                'nit' => '8.000.903.753',
                'cuentas' => $cuentas, // Usar cuentas únicas
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    function generateBalanceTercero(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial; //$request->fecha_inicial; // '2024-01-01'
            $fecha_final = $request->fecha_final; //$request->fecha_final; // '2024-02-01'

            // permitir rango de fecha solo maximo de 3 meses
            $fecha_final = date('Y-m-d', strtotime($fecha_final . '+ 3 months'));

            // validar que la fecha inicial sea menor que la fecha final
            if ($fecha_inicial > $fecha_final) {
                return response()->json(['status' => 400, 'message' => 'La fecha inicial no puede ser mayor que la fecha final'], 400);
            }

            // Validar que el rango de fecha incial y final solo tengan como maximo de 3 meses
            $fecha_inicial_dt = new DateTime($fecha_inicial);
            $fecha_final_dt = new DateTime($fecha_final);

            // Calcular la diferencia en meses
            $diferencia = $fecha_inicial_dt->diff($fecha_final_dt);
            $meses_diferencia = ($diferencia->y * 12) + $diferencia->m;

            // Validar que la diferencia no exceda 3 meses
            if ($meses_diferencia > 6) {
                return response()->json(['status' => 400, 'message' => 'El rango de fechas no puede ser mayor a 3 meses.'], 400);
            }

            // Obtener las cuentas con movimiento en el rango de fechas
            $cuentas = DB::table('vista_balance_tercero')
                ->whereBetween('fecha_comprobante', [$fecha_inicial, $fecha_final])
                ->select('puc', 'descripcion', 'tercero', 'saldo_anterior', 'debitos', 'creditos', 'saldo_nuevo');

            // Consulta adicional para incluir el registro con puc = 1
            $registro_adicional = DB::table('vista_balance_tercero')
                ->whereIn('puc', ['1', '11', '1105', '110505', '11050501', '110510', '11051001', '1110', '111005']) // Asegúrate de que este registro exista
                ->select('puc', 'descripcion', 'tercero', 'saldo_anterior', 'debitos', 'creditos', 'saldo_nuevo');

            // Combinar ambas consultas
            $cuentas_completas = $cuentas->union($registro_adicional)->distinct()->orderBy('puc')->get();

            $data = [
                'titulo' => 'Balance Tercero',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'tipo_balance' => 'balance_tercero',
                'nit' => '8.000.903.753',
                'cuentas' => $cuentas_completas, // Usar cuentas únicas
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    function generateBalanceComparativo(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial; //$request->fecha_inicial; // '2024-01-01'
            $fecha_final = $request->fecha_final; //$request->fecha_final; // '2024-02-01'

            // permitir rango de fecha solo maximo de 3 meses
            $fecha_final = date('Y-m-d', strtotime($fecha_final . '+ 3 months'));

            // validar que la fecha inicial sea menor que la fecha final
            if ($fecha_inicial > $fecha_final) {
                return response()->json(['status' => 400, 'message' => 'La fecha inicial no puede ser mayor que la fecha final'], 400);
            }

            /* // Validar que el rango de fecha incial y final solo tengan como maximo de 3 meses
            $fecha_inicial_dt = new DateTime($fecha_inicial);
            $fecha_final_dt = new DateTime($fecha_final);

            // Calcular la diferencia en meses
            $diferencia = $fecha_inicial_dt->diff($fecha_final_dt);
            $meses_diferencia = ($diferencia->y * 12) + $diferencia->m;

            // Validar que la diferencia no exceda 3 meses
            if ($meses_diferencia > 6) {
                return response()->json(['status' => 400, 'message' => 'El rango de fechas no puede ser mayor a 3 meses.'], 400);
            } */

            // Obtener año de la fecha incial
            $ano_inicial = date('Y', strtotime($fecha_inicial));

            // Obtener año de la fecha final
            $ano_final = date('Y', strtotime($fecha_final));

            $cuentas = DB::table('saldo_pucs as sp')
                ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
                ->selectRaw('sp.puc, ps.descripcion,
                    SUM(CASE WHEN CAST(sp.amo AS integer) BETWEEN ? AND ? THEN sp.saldo ELSE 0.00 END) AS "saldo"', [$ano_inicial, $ano_final])
                ->whereBetween(DB::raw('CAST(sp.amo AS integer)'), [$ano_inicial, $ano_final])
                ->whereIn('ps.grupo', ['1', '2', '3'])
                ->groupBy('sp.puc', 'ps.descripcion')
                ->orderBy('sp.puc')
                ->get();

            $total_saldo = $cuentas->sum('saldo');

            $data = [
                'titulo' => 'Balance Comparativo',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'tipo_balance' => 'balance_comparativo',
                'nit' => '8.000.903.753',
                'cuentas' => $cuentas, // Usar cuentas únicas
                'total_saldo' => $total_saldo,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    public function generateBalanceGeneral(Request $request)
    {
        try {
            // Validar parámetros
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

            // Validar que la fecha inicial sea el 1 de mes
            if (date('d', strtotime($fecha_inicial)) !== '01') {
                return response()->json(['status' => 400, 'message' => 'La fecha inicial debe ser el 1 de mes'], 400);
            }

            // Validar que el mes anterior a la fecha inical este cerrado
            if (validarCierreMes($fecha_inicial)) {
                return response()->json(['status' => 400, 'message' => 'El mes anterior a la fecha inicial debe estar cerrado'], 400);
            }

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
            $data = [
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

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $fecha_inicial = $request->fecha_inicial;
        $fecha_final = $request->fecha_final;
        $tipo_balance = $request->tipo_balance;

        switch ($tipo_balance) {
            case '2':
                $nombre = 'balance_horizontal_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
                break;
            case '3':
                $nombre = 'balance_terceros_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
                break;
            case '4':
                $nombre = 'balance_comparativo_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
                break;
            default:
                $nombre = 'balance_general_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
                break;
        }


        return Excel::download(new BalancesExport($tipo_balance, $fecha_inicial, $fecha_final), $nombre);
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
