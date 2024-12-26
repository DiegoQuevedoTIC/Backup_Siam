<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use App\Models\Comprobante;
use App\Models\Puc;
use App\Models\ComprobanteLinea;
use App\Models\InformacionFinanciera;
use App\Models\Tercero;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\ProgressBar;


class ImportCsvCommand extends Command
{
    protected $signature = 'import:csv';
    protected $description = 'Importar datos desde un archivo CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path('tercero_natural.csv');

        // Cargar el archivo CSV
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Si el CSV tiene encabezados

        //$data = [];

        // Leer los registros
        foreach ($csv as $record) {
            $data[] = $record; // Agregar cada registro al array
        }

        //d($data[1]);

        // Inicializar la barra de progreso
        $this->output->progressStart(count($data));

        // Procedemos a guardar las líneas para cada comprobante
        DB::transaction(function () use ($data) {
            foreach ($data as $linea) {
                /* $comprobante = Comprobante::where('n_documento', $linea['ENC_MOV_CONTA'])->first();
                $puc = Puc::where('puc', $linea['PUC'])->first();
                $tercero = Tercero::where('tercero_id', $linea['TERCERO'])->first();

                if ($comprobante && $puc) {
                    ComprobanteLinea::create([
                        'comprobante_id' => $comprobante->id,
                        'pucs_id' => $puc->id,
                        'tercero_id' => $tercero->id ?? null,
                        'descripcion_linea' => $linea['DETALLE'],
                        'debito' => !empty($linea['DEBITO']) ? $linea['DEBITO'] : null,
                        'credito' => !empty($linea['CREDITO']) ? $linea['CREDITO'] : null,
                        'linea' => !empty($linea['LINEA']) ? $linea['LINEA'] : null,
                        'BASE_GRAVABLE' => !empty($linea['BASE_GRAVABLE']) ? $linea['BASE_GRAVABLE'] : null,
                        'CHEQUE' => !empty($linea['CHEQUE']) ? $linea['CHEQUE'] : null
                    ]);
                } else {
                    Log::warning('Comprobante o PUC no encontrado', [
                        'n_documento' => $linea['ENC_MOV_CONTA'],
                        'puc' => $linea['PUC']
                    ]);
                } */



                $tercero = Tercero::where('tercero_id', $linea['TERCERO'])->first();
                if ($tercero) {

                    // Verifica que la fecha no esté vacía y que tenga el formato correcto
                    if (isset($linea['FECHA_NACIMIENTO']) && $linea['FECHA_NACIMIENTO']) {

                        $tercero->update([
                            'fecha_nacimiento' => $linea['FECHA_NACIMIENTO']
                        ]);
                    }

                    InformacionFinanciera::create([
                        'tercero_id' => $tercero->id,
                        'salario' => intval($linea['SUELDO']) ?? null,
                        'honorarios' => intval($linea['HONORARIOS']) ?? null,
                        'otros_ingresos' => intval($linea['OTROS_INGRESOS']) ?? null,
                        'gastos_sostenimiento' => intval($linea['GASTOS_SOSTENIMIENTO']) ?? null,
                        'otros_gastos' => intval($linea['OTROS_GASTOS']) ?? null,
                    ]);
                } else {

                }



                // Actualizar la barra de progreso
                $this->output->progressAdvance();
            }
        }, 5);

        // Finalizar la barra de progreso
        $this->output->progressFinish();

        $this->info('Operación completada con éxito.');
    }
}
