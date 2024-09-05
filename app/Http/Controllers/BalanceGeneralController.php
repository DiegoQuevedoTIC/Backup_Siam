<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class BalanceGeneralController extends Controller
{
    //

    /* public function generarPdf(Request $request)
    {
        try {

            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;


            $cuentas = DB::table('pucs as p')
                ->join('comprobante_lineas as cl', 'p.id', 'cl.pucs_id')
                ->leftJoin('comprobantes as c', 'c.id', 'cl.comprobante_id')
                ->whereBetween('c.fecha_comprobante', [$fecha_inicial, $fecha_final])
                ->select('p.puc', 'p.descripcion')
                ->orderBy('p.puc')
                ->get();

            $data = [
                'nombre_compania' => 'FONDEP',
                'cuentas' => $cuentas,
                'fecha_inicial' => $fecha_inicial,
                'fecha_final' => $fecha_final,
            ];

            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return response()->json(['pdf' => base64_encode($pdf->output())]);
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    } */

    public function generarPdf(Request $request)
    {
        try {
            $fecha_inicial = $request->fecha_inicial;
            $fecha_final = $request->fecha_final;

            $cuentas = DB::table('vista_balance_general')
                //->whereBetween('fecha_comprobante', [$fecha_inicial, $fecha_final]) comentado temporalmente
                ->select('puc', 'descripcion', 'saldo_anterior', 'debitos', 'creditos', 'saldo_nuevo')
                ->get();


            $data = [
                'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
                'nit' => '8.000.903.753',
                'cuentas' => $cuentas,
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
