<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Novedades extends Model
{
    use HasFactory;

    
    public function Tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }


    public function novedad(): BelongsTo
    {
        return $this->belongsTo(NovedadTercero::class);
    }
}
