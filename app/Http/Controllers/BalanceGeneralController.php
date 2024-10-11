<?php

namespace App\Http\Controllers;

use App\Exports\BalancesExport;
use App\Models\Comprobante;
use App\Models\ComprobanteLinea;
use App\Models\Puc;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Reader;

use function Laravel\Prompts\error;

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
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

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
            $pdfOutput = $pdf->output();
            $pdfBase64 = base64_encode($pdfOutput);

            // Generar el Excel en memoria
            $excelFileName = 'balance_horizontal_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
            $excelFile = Excel::raw(new BalancesExport($data), \Maatwebsite\Excel\Excel::XLSX);

            // Devolver ambos archivos
            return response()->json([
                'pdf' => $pdfBase64,
                'excel' => base64_encode($excelFile),
                'excel_file_name' => $excelFileName
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }

    function generateBalanceTercero(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

            // validar que la fecha inicial sea menor que la fecha final
            if ($fecha_inicial > $fecha_final) {
                return response()->json(['status' => 400, 'message' => 'La fecha inicial no puede ser mayor que la fecha final'], 400);
            }

            $cuentas = DB::select('SELECT * FROM obtener_saldos_terceros(?, ?)', [$fecha_inicial, $fecha_final]);

            $data = [
                'titulo' => 'Balance Tercero',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'tipo_balance' => 'balance_tercero',
                'nit' => '8.000.903.753',
                'cuentas' => $cuentas,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            // Generar el Excel en memoria
            $excelFileName = 'balance_terceros_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
            $excelFile = Excel::raw(new BalancesExport($data), \Maatwebsite\Excel\Excel::XLSX);

            // Devolver ambos archivos
            return response()->json([
                'excel' => base64_encode($excelFile),
                'excel_file_name' => $excelFileName
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Ocurrio un error!. Por favor intenta mas tarde.'], 500);
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
            $pdfOutput = $pdf->output();
            $pdfBase64 = base64_encode($pdfOutput);

            // Generar el Excel en memoria
            $excelFileName = 'balance_comparativo_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
            $excelFile = Excel::raw(new BalancesExport($data), \Maatwebsite\Excel\Excel::XLSX);

            // Devolver ambos archivos
            return response()->json([
                'pdf' => $pdfBase64,
                'excel' => base64_encode($excelFile),
                'excel_file_name' => $excelFileName
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
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

    public function generateBalanceGeneral(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

            $resultados = DB::select('SELECT * FROM obtener_saldos(?, ?)', [$fecha_inicial, $fecha_final]);

            // Convertir resultados a array
            $resultadosArray = json_decode(json_encode($resultados), true);

            // Totalizaciones
            $total_saldo_anteriores = array_sum(array_column($resultadosArray, 'saldo_anterior'));
            $total_debitos = array_sum(array_column($resultadosArray, 'total_debito'));
            $total_creditos = array_sum(array_column($resultadosArray, 'total_credito'));
            $total_saldo_nuevo = array_sum(array_column($resultadosArray, 'saldo_nuevo'));

            // Preparar los datos para el PDF
            $data = [
                'titulo' => 'Balance General',
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'nit' => '8.000.903.753',
                'tipo_balance' => 'balance_general',
                'cuentas' => array_values($resultadosArray),
                'total_saldo_anteriores' => $total_saldo_anteriores,
                'total_debitos' => $total_debitos,
                'total_creditos' => $total_creditos,
                'total_saldo_nuevo' => $total_saldo_nuevo,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            // Generar el PDF
            $pdf = Pdf::loadView('pdf.balance-general', $data);
            $pdfOutput = $pdf->output();
            $pdfBase64 = base64_encode($pdfOutput);

            // Generar el Excel en memoria
            $excelFileName = 'balance_general_' . $fecha_inicial . '_' . $fecha_final . '.xlsx';
            $excelFile = Excel::raw(new BalancesExport($data), \Maatwebsite\Excel\Excel::XLSX);

            // Devolver ambos archivos
            return response()->json([
                'pdf' => $pdfBase64,
                'excel' => base64_encode($excelFile),
                'excel_file_name' => $excelFileName
            ]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Ocurrio un error!, intentalo mas tarde.'], 500);
        }
    }
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
