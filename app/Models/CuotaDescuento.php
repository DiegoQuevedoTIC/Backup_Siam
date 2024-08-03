<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuotaDescuento extends Model
{
    use HasFactory;

    protected $table = 'cuotas_descuentos';

    protected $fillable = [
        'cliente',
        'con_descuento',
        'consecutivo',
        'nro_cuota',
        'fecha_vencimiento',
        'fecha_pago_total',
        'estado',
        'vlr_cuota',
        'abono_cuota',
        'vlr_interes',
        'abono_interes',
        'vlr_mora',
        'abono_mora',
        'congelada',
        'consecutivo_padre',
    ];
}
