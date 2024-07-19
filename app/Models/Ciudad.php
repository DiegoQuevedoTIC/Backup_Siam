<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Ciudad extends Model
{
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
 
    public function barrios(): HasMany
    {
        return $this->hasMany(Barrio::class);
    }

}

