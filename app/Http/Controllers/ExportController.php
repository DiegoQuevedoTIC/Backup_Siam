<?php

namespace App\Http\Controllers;

use App\Jobs\ExportPDFJob;
use App\Models\Asociado;
use App\Models\CreditoLinea;
use App\Models\InformacionFinanciera;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportSolicitudCredito(Request $request)
    {
        $asociado = Asociado::findOrFail($request->asociado);
        $finanzas = InformacionFinanciera::where('tercero_id', $asociado->tercero->id)->first();

        // Imprimimos la solicitud de credito
        $data = [
            'tercero' => $asociado->tercero,
            'credito' => $request->credito,
            'asociado' => $asociado,
            'finanzas' => $finanzas,
        ];

        // Cargar la vista y pasar los datos como un array
        $pdf = Pdf::loadView('pdf.solicitud_credito', $data);
        $pdfOutput = $pdf->output();
        $pdfBase64 = base64_encode($pdfOutput);

        return response()->json(['status' => 200, 'pdf' => $pdfBase64]);
    }
}
