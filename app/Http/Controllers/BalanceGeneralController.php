<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use Filament\Notifications\Notification;

class BalanceGeneralController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function generarPdf(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

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

            // Consulta original
            $cuentas = DB::table('vista_balance_general')
                ->whereBetween('fecha_comprobante', [$fecha_inicial, $fecha_final])
                ->select('puc', 'descripcion', 'saldo_anterior', 'debitos', 'creditos', 'saldo_nuevo');

            // Consulta adicional para incluir el registro con puc = 1
            $registro_adicional = DB::table('vista_balance_general')
                ->whereIn('puc', ['1', '11', '1105', '110505', '11050501', '110510', '11051001', '1110', '111005']) // Asegúrate de que este registro exista
                ->select('puc', 'descripcion', 'saldo_anterior', 'debitos', 'creditos', 'saldo_nuevo');

            // Combinar ambas consultas
            $cuentas_completas = $cuentas->union($registro_adicional)->orderBy('puc')->get();


            $data = [
                'titulo' => 'Balance General',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'nit' => '8.000.903.753',
                'tipo_balance' => 'balance_general',
                'cuentas' => $cuentas_completas,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
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
}
