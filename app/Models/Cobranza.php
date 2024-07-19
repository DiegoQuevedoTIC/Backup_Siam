<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobranza extends Model
{
    use HasFactory;

    protected $table = 'cobranzas';

    protected $fillable = [
        'asociado_id',
        'fecha_gestion',
        'nro_producto',
        'tipo_gestion',
        'detalles_gestion',
        'resultado',
        'usuario_gestion',
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
