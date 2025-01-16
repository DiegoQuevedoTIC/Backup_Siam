<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obligacion extends Model
{
    use HasFactory;

    protected $table = 'detalle_vencimiento_descuento';

    protected $fillable = [
        'asociado_id',
        'concepto',
        'aportes',
        'valor_descuento',
        'plazo',
        'periodo_descuento',
        'fecha_limite_pago',
        'fecha_inicio_descuento',
        'nro_cuota',
        'vigente',
        'vencida',
        'limite_cuotas',
        'fecha_ultima_couta'
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
