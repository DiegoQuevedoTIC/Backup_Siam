<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CierreMensual extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha_cierre',
        'mes_cierre',
        'user_id'
    ];

    protected $table = 'cierre_mensuales';


    public function detalles(): HasMany
    {
        return $this->hasMany(CierreMensualDetalle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
