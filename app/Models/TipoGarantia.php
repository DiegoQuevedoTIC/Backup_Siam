<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGarantia extends Model
{
    use HasFactory;

    protected $table = 'tipo_garantias';

    protected $fillable = [
        'nombre',
        'clasificacion',
        'descripcion',
    ];

    public function garantias()
    {
        return $this->hasMany(Garantia::class);
    }
}
