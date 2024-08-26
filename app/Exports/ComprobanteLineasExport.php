<?php

namespace App\Exports;

use App\Models\ComprobanteLinea;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComprobanteLineasExport implements FromQuery, WithHeadings
{
    use Exportable;

    public $comprobante;

    public function __construct(int $comprobante)
    {
        $this->comprobante = $comprobante;
    }

    public function query()
    {
        return ComprobanteLinea::query()->where('comprobante_id', $this->comprobante)->join('pucs as p', 'pucs_id', 'p.id')->select('p.puc', 'descripcion_linea', 'debito', 'credito', 'linea', 'BASE_GRAVABLE', 'CHEQUE');
    }

    public function headings(): array
    {
        return [
            'PUC',
            'DESCRIPCION',
            'DEBITO',
            'CREDITO',
            'LINEA',
            'BASE_GRAVABLE',
            'CHEQUE'
        ];
    }
}
