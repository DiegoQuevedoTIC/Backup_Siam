<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreAnualDetalle extends Model
{
    use HasFactory;

    protected $table = 'cierre_anual_detalles';

    public function cierreAnual(): BelongsTo
    {
        return $this->belongsTo(CierreAnual::class);
    }
}
