<?php

namespace App\Console\Commands;

use App\Imports\SaldoPucImport;
use App\Models\SaldoPuc;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportSaldoPucXlsx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-saldo-puc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Se importan los saldos pucs mediante archivo excel
    ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = public_path('2014.xlsx');
        $datos = Excel::toArray(new SaldoPucImport, $filePath);

        $data = [];

        // Leer los registros
        foreach ($datos as $record) {
            $data[] = $record; // Agregar cada registro al array
        }

        // Inicializar la barra de progreso
        $this->output->progressStart(count($data));

        foreach ($datos as $dato) {
            usort($dato, function ($a, $b) {
                return $a['agencia'] <=> $b['agencia']; // Operador de nave espacial para comparación
            });

            foreach ($dato as $d) {
                if ($d['agencia'] === 1) {
                    SaldoPuc::create([
                        'puc' => $d['puc'],
                        'amo' => $d['amo'],
                        'mes' => $d['mes'],
                        'saldo_debito' => $d['saldo_debito'] ?? 0,
                        'saldo_credito' => $d['saldo_credito'] ?? 0,
                        'saldo' => $d['saldo'] ?? 0,
                    ]);
                }

                if ($d['agencia'] === 2) {
                    $cuenta = SaldoPuc::where('puc', $d['puc'])
                        ->where('amo', $d['amo'])
                        ->where('mes', $d['mes'])
                        ->first();

                    if ($cuenta) {
                        $cuenta->saldo_debito += $d['saldo_debito'] ?? 0;
                        $cuenta->saldo_credito += $d['saldo_credito'] ?? 0;
                        $cuenta->saldo += $d['saldo'] ?? 0;
                        $cuenta->save();
                    }
                }

                // Actualizar la barra de progreso
                $this->output->progressAdvance();
            }
        }

        // Finalizar la barra de progreso
        $this->output->progressFinish();

        $this->info('Los saldos PUC se importaron correctamente.');
    }
}
