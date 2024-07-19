<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreMensualDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'cierre_mensual_id',
        'puc_id',
        'debito',
        'credito',
        'saldo_anterior',
        'saldo_actual'
    ];


    public function puc(): BelongsTo
    {
        return $this->belongsTo(Puc::class);
    }

    public function cierreMensual(): BelongsTo
    {
        return $this->belongsTo(CierreMensual::class);
    }
}
