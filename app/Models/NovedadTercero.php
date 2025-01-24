<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NovedadTercero extends Model
{
    use HasFactory;

    protected $table = 'novedad_terceros';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'cambiaestado',
        'estado_cliente_id'
    ];

    public function EstadoCliente(): BelongsTo
    {
        return $this->belongsTo(EstadoCliente::class);
    }


}
