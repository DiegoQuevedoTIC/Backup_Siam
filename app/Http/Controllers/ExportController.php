<?php

namespace App\Http\Controllers;

use App\Jobs\ExportPDFJob;
use App\Models\Asociado;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function exportSolicitudCredito(Request $request)
    {
        $asociado = Asociado::findOrFail($request->asociado);

        // Imprimimos la solicitud de credito
        $tercero = $asociado->tercero;

        // Asegúrate de que 'tercero' sea un objeto que contenga el teléfono
        $data = [
            'telefono' => $tercero->telefono, // Cambia esto si necesitas más datos
        ];

        // Cargar la vista y pasar los datos como un array
        $pdf = Pdf::loadView('pdf.solicitud_credito', $data);
        $pdfOutput = $pdf->output();
        $pdfBase64 = base64_encode($pdfOutput);

        return response()->json(['status' => 200, 'pdf' => $pdfBase64]);
    }
}
