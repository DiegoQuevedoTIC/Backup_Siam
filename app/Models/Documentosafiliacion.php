<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Documentosafiliacion extends Model
{
    use HasFactory;


        public function documentoclase(): BelongsTo
    {
        return $this->BelongsTo(Documentoclase::class);
    }

        public function tercero(): BelongsTo
    {
        return $this->BelongsTo(Tercero::class);
    }

}
