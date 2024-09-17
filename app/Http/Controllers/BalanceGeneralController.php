<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;

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

            // Almacenar todas las cuentas PUC
            $cuentas_puc = DB::table('pucs')->select('id', 'puc', 'descripcion', 'grupo', 'puc_padre')->get()->toArray();

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

                $movimientos_por_cuenta[$puc->id] = [
                    'puc' => $puc->puc,
                    'descripcion' => $puc->descripcion,
                    'debitos' => $movimiento->debitos ?? 0,
                    'creditos' => $movimiento->creditos ?? 0,
                    'saldo_nuevo' => $movimiento->saldo_nuevo ?? $saldo_anterior,
                    'saldo_anterior' => $saldo_anterior,
                    'puc_padre' => $puc->puc_padre,
                ];
            }

            // Sumar los movimientos de las cuentas hijas a las cuentas padres
            foreach ($cuentas_puc as $puc) {
                sumarMovimientosPadres($puc->id, $movimientos_por_cuenta, $pucs_normalizados);
            }

            // Filtrar resultados para incluir solo cuentas con movimientos
            $resultados = array_filter($movimientos_por_cuenta, function ($mov) {
                return $mov['debitos'] > 0 || $mov['creditos'] > 0 || $mov['saldo_anterior'] > 0;
            });

            // Preparar los datos para el PDF
            $data = [
                'titulo' => 'Balance General',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'nit' => '8.000.903.753',
                'tipo_balance' => 'balance_general',
                'cuentas' => array_values($resultados),
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Ocurrio un error, por favor intenta mas tarde.'], 500);
        }
    }
}

// Función recursiva para sumar movimientos de cuentas hijas a cuentas padres
function sumarMovimientosPadres($puc_id, &$movimientos_por_cuenta, $pucs_normalizados)
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
                        'saldo_nuevo' => 0,
                        'saldo_anterior' => 0,
                    ];
                }

                // Sumar los movimientos de la cuenta hija al padre
                $movimientos_por_cuenta[$padre_id]['debitos'] += $puc['debitos'];
                $movimientos_por_cuenta[$padre_id]['creditos'] += $puc['creditos'];
                $movimientos_por_cuenta[$padre_id]['saldo_nuevo'] += $puc['saldo_nuevo'];

                // Llamar recursivamente para el padre
                sumarMovimientosPadres($padre_id, $movimientos_por_cuenta, $pucs_normalizados);
            }
        }
    }
}

function buscarSaldoAnterior($fecha_inicial, $puc)
{
    // Obtener año y mes de la fecha inicial
    $ano_inicial = date('Y', strtotime($fecha_inicial));
    $mes_inicial = date('n', strtotime($fecha_inicial));

    // Consultar el saldo anterior
    $cuenta = DB::table('saldo_pucs')
        ->where('amo', $ano_inicial)
        ->where('mes', $mes_inicial)
        ->where('puc', $puc)
        ->orderBy('id', 'DESC')
        ->first();

    return $cuenta->saldo ?? 0.00;
}

function buscarMovimientos($fecha_inicial, $fecha_final)
{
    return DB::table('comprobantes as c')
        ->join('comprobante_lineas as cl', 'cl.comprobante_id', 'c.id')
        ->leftJoin('pucs as p', 'cl.pucs_id', 'p.id')
        ->whereBetween('c.fecha_comprobante', [$fecha_inicial, $fecha_final])
        ->select(
            'p.puc',
            DB::raw('SUM(CASE WHEN cl.debito > 0 THEN cl.debito ELSE 0.00 END) AS debitos'),
            DB::raw('SUM(CASE WHEN cl.credito > 0 THEN cl.credito ELSE 0.00 END) AS creditos'),
            DB::raw('CASE
                WHEN p.naturaleza = \'D\' THEN SUM(cl.debito) - SUM(cl.credito)
                WHEN p.naturaleza = \'C\' THEN SUM(cl.credito) - SUM(cl.debito)
            END AS saldo_nuevo')
        )
        ->groupBy('p.puc', 'p.naturaleza')
        ->get();
}
