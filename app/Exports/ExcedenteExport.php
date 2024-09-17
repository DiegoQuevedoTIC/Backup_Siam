<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class ExcedenteExport implements FromView
{
    public $fecha_inicial, $fecha_final, $tipo_excedente;

    public function __construct($fecha_inicial, $fecha_final, $tipo_excedente)
    {
        $this->fecha_inicial = $fecha_inicial;
        $this->fecha_final = $fecha_final;
        $this->tipo_excedente = $tipo_excedente;
    }

    public function view(): View
    {
        $cuentas = DB::table('users')->get();

        $data = [
            'titulo' => $this->tipo_excedente,
            'nombre_compania' => 'GRUPO FINANCIERO - FONDEP',
            'tipo_balance' => 'balance_comparativo',
            'nit' => '8.000.903.753',
            'cuentas' => $cuentas,
            'fecha_inicial' => $this->fecha_inicial,
            'fecha_final' => $this->fecha_final,
            'total_saldo' => 0, // Aquí se debe calcular el total_saldo
        ];

        return view('pdf.excedentepyg', $data);
    }
}
