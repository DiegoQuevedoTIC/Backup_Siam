<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComprobanteLinea extends Model
{
    use HasFactory;

    protected $fillable = ['pucs_id', 'tercero_registro', 'descripcion_linea', 'debito', 'credito', 'comprobante_id'];

    protected $table = 'comprobante_lineas';

    public function comprobante(): BelongsTo
    {
        return $this->belongsTo(Comprobante::class);
    }


    public function tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }

    public function puc(): BelongsTo
    {
        return $this->belongsTo(Puc::class);
    }
}
