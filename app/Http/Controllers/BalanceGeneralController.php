<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Exception;

class BalanceGeneralController extends Controller
{
    //

    public function generarPdf()
    {
        try {
            $data = [
                'nombre_compania' => 'FONDEP',
                'data' => [

                ]
            ];
            $pdf = Pdf::loadView('pdf.balance-general', $data);
            return $pdf->stream('balance_de_prueba.pdf');
        } catch (Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()], 500);
        }
    }
}
