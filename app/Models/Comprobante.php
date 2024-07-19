<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comprobante extends Model
{
    use HasFactory;

    protected $fillable = ['tipo_documento_contables_id', 'n_documento', 'tercero_comprobante', 'is_plantilla', 'descripcion_comprobante'];

    public function comprobanteLinea(): HasMany
    {
        return $this->HasMany(ComprobanteLinea::class);
    }

    public function tipoDocumentoContable(): BelongsTo
    {
        return $this->belongsTo(TipoDocumentoContable::class);
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
