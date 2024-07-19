<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barrio extends Model
{

    public function novedadtercero(): HasMany
    {
        return $this->hasMany(NovedadTercero::class);
    }

    public function terceros(): HasMany
    {
        return $this->hasMany(Tercero::class);
    }

}

