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
        'clasificacion_id',
        'tipo_garantia_id',
        'tipo_inversion_id',
        'moneda_id',
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

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest_record = CreditoLinea::orderBy('created_at', 'DESC')->first();
            $record_number = $latest_record ? $latest_record->id : 1;

            $model->linea = str_pad($record_number + 1, 8, '0', STR_PAD_LEFT);
        });
    }

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

    public function creditoSolicitudes()
    {
        return $this->hasMany(CreditoSolicitud::class, 'linea', 'id');
    }
}
