<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Puc extends Model
{
    use HasFactory;

    protected $fillable = [
        'puc',
        'grupo',
        'descripcion',
        'nivel',
        'naturaleza',
        'mayor_rep',
        'movimiento',
        'subcentro',
        'bancaria',
        'tercero',
        'puc_padre',
        'base_gravable',
        'mueve_modulo',
        'codigo_dian',
    ];


    public function comprobanteLinea(): HasMany
    {
        return $this->hasMany(ComprobanteLinea::class);
    }

    public function pucs(): HasMany
    {
        return $this->hasMany(Puc::class, 'pucs_id');
    }

    public function allPucs()
    {
        return $this->pucs()->with('allPucs');
    }

    public function detalleCierreMensual(): HasMany
    {
        return $this->hasMany(CierreMensualDetalle::class);
    }
}
