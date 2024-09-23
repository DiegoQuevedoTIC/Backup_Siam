<?php

namespace App\Filament\Resources\ExcedenteResource\Pages;

use App\Filament\Resources\ExcedenteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CreateExcedente extends CreateRecord
{
    protected static string $resource = ExcedenteResource::class;

    protected static string $view = 'custom.excedentepyg.create';

    public $showPDF = false, $src;

    public function exportPDF()
    {
        // Obtener año de la fecha incial
        $ano_inicial = date('Y', strtotime($this->data['fecha_desde']));

        // Obtener año de la fecha final
        $ano_final = date('Y', strtotime($this->data['fecha_hasta']));


        switch ($this->data['tipo_informe']) {
            case '2':
                $data = $this->detallado($ano_inicial, $ano_final);
                break;
            case '3':
                $data = $this->comparativo($ano_inicial, $ano_final, $this->data['fecha_comparacion_desde'], $this->data['fecha_comparacion_hasta']);
                break;
            default:
                $data = $this->standard($ano_inicial, $ano_final);
        }

        $pdf = Pdf::loadView('pdf.excedentepyg', $data);
        $this->src = 'data:application/pdf;base64,' . base64_encode($pdf->output());
        $this->showPDF = true;
    }



    function standard($fecha_inicial, $fecha_final): array
    {
        $cuentas = DB::table('saldo_pucs as sp')
            ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
            ->selectRaw('sp.puc, ps.descripcion,
                SUM(CASE
                    WHEN ps.naturaleza = \'D\' THEN sp.saldo_debito
                    WHEN ps.naturaleza = \'C\' THEN -sp.saldo_credito
                    ELSE 0
                END) AS saldo')
            ->whereIn('ps.grupo', ['4', '5', '6'])
            ->where('ps.nivel', '3')
            ->whereBetween('sp.amo', [$fecha_inicial, $fecha_final])
            ->groupBy('sp.puc', 'ps.descripcion')
            ->orderBy('sp.puc')
            ->get();


        // Filtrar ingresos y egresos
        $ingresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '4') === 0; // Cuentas que comienzan con 4
        });

        $egresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '5') === 0 || strpos($cuenta->puc, '6') === 0; // Cuentas que comienzan con 5 o 6
        });

        //total ingresos
        $total_ingresos = $ingresos->sum('saldo');

        // total egresos
        $total_egresos = $egresos->sum('saldo');

        // total saldo
        $total_saldo = $total_ingresos - $total_egresos;

        // Convertir las fechas a objetos DateTime
        $fecha_inicial = new \DateTime($this->data['fecha_desde']);
        $fecha_final = new \DateTime($this->data['fecha_hasta']);

        return [
            'titulo' => 'Reporte Excedente PYG Standard',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'tipo_informe' => '1',
            'nit' => '8.000.903.753',
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'total_ingresos' => $total_ingresos,
            'total_egresos' => $total_egresos,
            'total_saldo' => $total_saldo,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
        ];
    }


    function detallado($fecha_inicial, $fecha_final): array
    {
        $cuentas = DB::table('saldo_pucs as sp')
            ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
            ->selectRaw('sp.puc, ps.descripcion,
                SUM(CASE
                    WHEN ps.naturaleza = \'D\' THEN sp.saldo_debito
                    WHEN ps.naturaleza = \'C\' THEN -sp.saldo_credito
                    ELSE 0
                END) AS saldo')
            ->whereIn('ps.grupo', ['4', '5', '6'])
            ->whereBetween('sp.amo', [$fecha_inicial, $fecha_final])
            ->groupBy('sp.puc', 'ps.descripcion')
            ->orderBy('sp.puc')
            ->get();


        // Filtrar ingresos y egresos
        $ingresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '4') === 0; // Cuentas que comienzan con 4
        });

        $egresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '5') === 0 || strpos($cuenta->puc, '6') === 0; // Cuentas que comienzan con 5 o 6
        });

        //total ingresos
        $total_ingresos = $ingresos->sum('saldo');

        // total egresos
        $total_egresos = $egresos->sum('saldo');

        // total saldo
        $total_saldo = $total_ingresos - $total_egresos;

        // Convertir las fechas a objetos DateTime
        $fecha_inicial = new \DateTime($this->data['fecha_desde']);
        $fecha_final = new \DateTime($this->data['fecha_hasta']);

        return [
            'titulo' => 'Reporte Excedente PYG Detallado',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'tipo_informe' => '2',
            'nit' => '8.000.903.753',
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'total_ingresos' => $total_ingresos,
            'total_egresos' => $total_egresos,
            'total_saldo' => $total_saldo,
            'fecha_inicial' => $fecha_inicial,
            'fecha_final' => $fecha_final,
        ];
    }

    function comparativo($fecha_inicial, $fecha_final, $fecha_com_inicial, $fecha_com_final): array
    {
        // Consulta para el primer rango de fechas
        $cuentas = DB::table('saldo_pucs as sp')
            ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
            ->selectRaw('sp.puc, ps.descripcion,
            SUM(CASE
                WHEN ps.naturaleza = \'D\' THEN sp.saldo_debito
                WHEN ps.naturaleza = \'C\' THEN -sp.saldo_credito
                ELSE 0
            END) AS saldo')
            ->whereIn('ps.grupo', ['4', '5', '6'])
            ->where('ps.nivel', '3')
            ->whereBetween('sp.amo', [$fecha_inicial, $fecha_final])
            ->groupBy('sp.puc', 'ps.descripcion')
            ->orderBy('sp.puc')
            ->get();

        // Consulta para el segundo rango de fechas
        $cuentas_comparativo = DB::table('saldo_pucs as sp')
            ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
            ->selectRaw('sp.puc, ps.descripcion,
            SUM(CASE
                WHEN ps.naturaleza = \'D\' THEN sp.saldo_debito
                WHEN ps.naturaleza = \'C\' THEN -sp.saldo_credito
                ELSE 0
            END) AS saldo')
            ->whereIn('ps.grupo', ['4', '5', '6'])
            ->where('ps.nivel', '3')
            ->whereBetween('sp.amo', [$fecha_com_inicial, $fecha_com_final])
            ->groupBy('sp.puc', 'ps.descripcion')
            ->orderBy('sp.puc')
            ->get();

        // Filtrar ingresos y egresos para el primer rango
        $ingresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '4') === 0; // Cuentas que comienzan con 4
        });

        $egresos = $cuentas->filter(function ($cuenta) {
            return strpos($cuenta->puc, '5') === 0 || strpos($cuenta->puc, '6') === 0; // Cuentas que comienzan con 5 o 6
        });

        // Total ingresos y egresos para el primer rango
        $total_ingresos = $ingresos->sum('saldo');
        $total_egresos = $egresos->sum('saldo');
        $total_saldo = $total_ingresos - $total_egresos;

        // Crear un array para almacenar los resultados comparativos
        $resultados_comparativos = [];

        // Combinar los resultados de ambos rangos
        foreach ($cuentas as $cuenta) {
            $saldo_comparativo = $cuentas_comparativo->firstWhere('puc', $cuenta->puc);
            $resultados_comparativos[] = [
                'puc' => $cuenta->puc,
                'descripcion' => $cuenta->descripcion,
                'saldo_rango_1' => $cuenta->saldo,
                'saldo_rango_2' => $saldo_comparativo ? $saldo_comparativo->saldo : 0,
            ];
        }

        return [
            'titulo' => 'Reporte Excedente PYG Comparativo',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'tipo_informe' => '3',
            'nit' => '8.000.903.753',
            'resultados_comparativos' => $resultados_comparativos,
            'total_ingresos' => $total_ingresos,
            'total_egresos' => $total_egresos,
            'total_saldo' => $total_saldo,
            'fecha_inicial' => new \DateTime($fecha_inicial),
            'fecha_final' => new \DateTime($fecha_final),
        ];
    }
}
