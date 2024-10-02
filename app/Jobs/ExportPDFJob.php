<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExportPDFJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 0;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        // Obtener el ID del Job
        $jobId = $this->job->getJobId();

        error_log(json_encode($jobId));

        // Actualizar el estado a 'pending'
        DB::table('jobs_status')->insert([
            'job_id' => $jobId,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            // Lógica para generar el PDF
            $pdf = Pdf::loadView('pdf.auxiliar_tercero', $this->data);
            $pdfOutput = base64_encode($pdf->output());

            // Guardar el PDF en el sistema de archivos
            Storage::put('pdfs/auxiliar_tercero.pdf', $pdfOutput);

            // Actualizar el estado a 'completed'
            DB::table('jobs_status')
                ->where('job_id', $jobId)
                ->update(['status' => 'completed', 'updated_at' => now()]);
        } catch (\Exception $e) {
            // Actualizar el estado a 'failed'
            error_log(json_encode($e->getMessage()));
            DB::table('jobs_status')
                ->where('job_id', $jobId)
                ->update(['status' => 'failed', 'updated_at' => now()]);
        }
    }
}
