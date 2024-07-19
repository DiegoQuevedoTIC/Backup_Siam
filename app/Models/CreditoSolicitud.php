<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'credito_solicitudes';

    protected $fillable = [
        'linea',
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $latest_record = CreditoSolicitud::orderBy('created_at', 'DESC')->first();
            $record_number = $latest_record ? $latest_record->id : 1;

            $model->solicitud = str_pad($record_number + 1, 8, '0', STR_PAD_LEFT);
        });
    }

    public function asociado()
    {
        return $this->belongsTo(Asociado::class, 'asociado_id');
    }

    public function principalCreditoCuota()
    {
        return $this->hasMany(PrincipalCreditoCuota::class, 'credito_solicitud_id');
    }
}
