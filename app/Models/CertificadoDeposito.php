<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoDeposito extends Model
{
    use HasFactory;

   /*  protected $table = 'certificado_depositos'; */
    protected $table = 'cdts';

/*     protected $fillable = [
        'tasa',
        'plazo_inversion',
        'valor_inicial_cdat',
        'valor_proyectado',
        'tasa_interes_remuneracion',
        'porcentaje_retencion',
        'nro_prorroga',
        'codigo_asesor',
        'nombre_asesor',
        'observaciones',
        'valor_apertura',
        'fecha_apertura',
        'valor_a_pagar',
        'fecha_cancelacion',
        'asociado_id',
    ]; */


    protected $fillable = [
        'numero_cdt',
        'user_id',
        'titular_id',
        'valor',
        'plazo',
        'tasa_interes',
        'tasa_ea',
        'fecha_creacion',
        'fecha_ultima_renovacion',
        'fecha_vencimiento',
        'estado',
        'interes_generados',
        'contabilizado',
        'fecha_cancelacion',
        'observaciones',

    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class, 'titular_id', 'id');
    }
}
