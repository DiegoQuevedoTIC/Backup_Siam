<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Parentesco;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ParametrosTercero extends Model
{
    use HasFactory;


    
    public function parentescos(): MorphMany
    {
        return $this->morphMany(Parentesco::class, 'pariente');
    }
}
