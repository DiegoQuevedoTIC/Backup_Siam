<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CierreAnual extends Model
{
    use HasFactory;

    protected $table = 'cierre_anuales';


    public function detalles(): HasMany
    {
        return $this->hasMany(CierreAnualDetalle::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
