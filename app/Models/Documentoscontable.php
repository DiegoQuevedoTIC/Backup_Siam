<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Documentoscontable extends Model
{
    use HasFactory;

    protected $table = 'documentoscontables';

        public function documentotipo(): BelongsTo
    {
        return $this->BelongsTo(Documentotipo::class);
    }

        public function documentoclases(): HasMany
    {
        return $this->hasMany(Documentoclase::class);
    }


}
