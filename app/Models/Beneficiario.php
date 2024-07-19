<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;

    protected $table = 'beneficiarios';

    protected $fillable = [
        'asociado_id',
        'nro_identi_beneficiario',
        'nombre_beneficiario',
        'porcentaje_titulo',
        'observaciones'
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
