<?php

namespace App\Imports;

use App\Models\ComprobanteLinea;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ComprobanteLineaImport implements ToModel, WithHeadingRow
{
    public $comprobante_id;
    public $linea = 0;


    public function __construct($comprobante_id)
    {
        $this->comprobante_id = $comprobante_id;

        $lastLine = DB::table('comprobantes as c')
            ->join('comprobante_lineas as cl', 'c.id', 'cl.comprobante_id')
            ->where('c.id', $this->comprobante_id)
            ->orderBy('cl.linea', 'desc')
            ->value('cl.linea');

        $this->linea = $lastLine ? $lastLine + 1 : 1;
    }

    public function model(array $row)
    {
        $comprobanteLinea = new ComprobanteLinea([
            'pucs_id'         => DB::table('pucs')->where('puc', $row['puc'])->first()->id,
            'tercero_id'      => DB::table('terceros')->where('tercero_id', $row['tercero'])->first()->id,
            'descripcion_linea' => $row['descripcion'],
            'debito'          => $row['debito'],
            'credito'         => $row['credito'],
            'comprobante_id'  => $this->comprobante_id,
            'linea'          => $this->linea,
        ]);

        $this->linea++; // Incrementa para la próxima línea

        return $comprobanteLinea;
    }
}
