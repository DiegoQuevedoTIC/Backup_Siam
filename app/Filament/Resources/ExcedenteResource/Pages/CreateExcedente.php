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

        $cuentas = DB::table('saldo_pucs as sp')
            ->join('pucs as ps', 'sp.puc', '=', 'ps.puc')
            ->selectRaw('sp.puc, ps.descripcion, SUM(CASE WHEN CAST(sp.amo AS integer) BETWEEN ? AND ? THEN sp.saldo ELSE 0.00 END) AS "saldo"', [$ano_inicial, $ano_final])
            ->whereBetween(DB::raw('CAST(sp.amo AS integer)'), [$ano_inicial, $ano_final])
            ->whereIn('ps.grupo', ['4', '5', '6'])
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

        $data = [
            'titulo' => 'Reporte Excedente PYG Standard',
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'tipo_informe' => '1',
            'nit' => '8.000.903.753',
            'ingresos' => $ingresos,
            'egresos' => $egresos,
            'total_ingresos' => $total_ingresos,
            'total_egresos' => $total_egresos,
            'total_saldo' => $total_saldo,
            'fecha_inicial' => $this->data['fecha_desde'],
            'fecha_final' => $this->data['fecha_hasta'],
        ];

        $pdf = Pdf::loadView('pdf.excedentepyg', $data);
        $this->src = 'data:application/pdf;base64,' . base64_encode($pdf->output());
        $this->showPDF = true;
    }
}
