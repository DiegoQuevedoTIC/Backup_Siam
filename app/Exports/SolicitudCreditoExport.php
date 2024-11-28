<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class SolicitudCreditoExport implements FromView
{
    use Exportable;

    public $tercero;

    public function __construct($tercero)
    {
        $this->tercero = $tercero;
    }
    public function view(): View
    {
        return view('pdf.solicitud_credito', [
            'tercero' => $this->tercero
        ]);
    }
}
