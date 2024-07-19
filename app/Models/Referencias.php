<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Referencias extends Model
{
    use HasFactory;

    public function Tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }


    
    public function Parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class);
    }
}
