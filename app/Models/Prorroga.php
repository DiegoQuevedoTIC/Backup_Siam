<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prorroga extends Model
{
    use HasFactory;

    protected $table = 'prorrogas';

    protected $fillable = [
        'asociado_id',
        'plazo_inversion',
        'valor_inicial_cdat',
        'valor_prorroga',
        'tasa_interes_remuneracion',
        'porcentaje_retencion',
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
