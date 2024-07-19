<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditoLinea extends Model
{
    use HasFactory;
    protected $table = 'credito_lineas';
    protected $fillable = [
        'descripcion',
        'clasificacion',
        'tipo_garantia',
        'tipo_inversion',
        'moneda',
        'periodo_pago',
        'interes_cte',
        'interes_mora',
        'tipo_cuota',
        'tipo_tasa',
        'nro_cuotas_max',
        'nro_cuotas_gracia',
        'cant_gar_real',
        'cant_gar_pers',
        'monto_min',
        'monto_max',
        'abonos_extra',
        'ciius',
        'subcentro',
    ];

    public function moneda()
    {
        return $this->belongsTo(Moneda::class);
    }
    public function clasificacion()
    {
        return $this->belongsTo(ClasificacionCredito::class);
    }
    public function tipoInversion()
    {
        return $this->belongsTo(tipoInversion::class);
    }
    public function tipoGarantia()
    {
        return $this->belongsTo(tipoGarantia::class);
    }
    public function periodoPago()
    {
        return $this->belongsTo(periodoPago::class);
    }



}
