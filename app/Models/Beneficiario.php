<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;

    protected $table = 'cdt_titulares';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'es_cotitular',
        'cdt_numero',
        'porcentaje_titulo',
        'observaciones'
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
