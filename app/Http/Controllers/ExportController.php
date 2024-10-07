<?php

namespace App\Http\Controllers;

use App\Jobs\ExportPDFJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    //

    public function exportPDF(Request $request)
    {
        $data = $request->all(); // Obtener los datos necesarios

        // Despachar el Job
        $job = ExportPDFJob::dispatch($data);

        // Devolver el ID del Job para poder consultarlo después
        return response()->json(['job_id' => $job]);
    }

    public function checkJobStatus($jobId)
    {
        // Verificar el estado del Job en la base de datos
        $jobStatus = DB::table('jobs_status')->where('job_id', $jobId)->first();

        if ($jobStatus) {
            if ($jobStatus->status === 'completed') {
                error_log('verdadero');
                return response()->json(['status' => 'completed']);
            } elseif ($jobStatus->status === 'failed') {
                error_log('failed');
                return response()->json(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'not found']);
    }
}
