<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patrimonio extends Model
{
    use HasFactory;

    protected $table = 'patrimonio';

    protected $fillable = [
        'tercero_id',
        'tipo',
        'tipo_inmueble',
        'direccion',
        'valor_comercial_inmueble',
        'hipoteca_favor',
        'valor_pendiente_pago_inmueble',
        'vehiculo_clase',
        'valor_comercial_vehiculo',
        'marca_referencia',
        'numero_placa',
        'modelo',
        'valor_pendiente_pago_vehiculo',
        'reserva_dominio',
        'descripcion_otros',
        'valor_comercial_otros',
        'pignorado'
    ];

    public function Tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }

/*     public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    } */
}
