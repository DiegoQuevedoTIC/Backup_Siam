<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Documentotipo extends Model
{
        public function documentoclase(): BelongsTo
    {
        return $this->belongsTo(Documentoclase::class);
    }


        public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class);
    }


            public function documentoscontables(): HasMany
    {
        return $this->hasMany(Documentoscontable::class);
    }



}
