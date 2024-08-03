<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoDescuento extends Model
{
    use HasFactory;

    protected $table = 'historico_descuentos';

    protected $fillable = [
        'cliente',
        'con_descuento',
        'linea',
        'con_servicio',
        'fecha',
        'hora',
        'grupo_docto',
        'compania_docto',
        'agencia_docto',
        'tdocto',
        'nro_docto',
        'vlr_debito',
        'vlr_credito',
    ];
}
