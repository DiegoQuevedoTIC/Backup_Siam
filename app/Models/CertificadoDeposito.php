<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificadoDeposito extends Model
{
    use HasFactory;


    protected $table = 'cdts';


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
