<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parentesco extends Model
{
    use HasFactory;


    public function pariente(): MorphTo
    {
        return $this->morphTo();
    }
}
