<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentralRiesgo extends Model
{


    protected $table = 'cartera_encabezados_corte';

    protected $fillable = [
        'tdocto',
        'nro_docto',
        'cliente',
        'linea',
        'estado',
        'fecha_docto',
        'fecha_primer_vto',
        'vlr_docto_vto',
        'vlr_saldo_actual',
        'fecha_corte',
    ];

}
