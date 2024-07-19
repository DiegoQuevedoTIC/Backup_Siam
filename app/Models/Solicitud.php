<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Solicitud extends Model
{

    public function tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }


    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function documentoclase(): BelongsTo
    {
        return $this->belongsTo(Documentoclase::class);
    }
}
