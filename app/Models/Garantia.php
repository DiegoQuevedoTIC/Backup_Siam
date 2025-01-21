<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garantia extends Model
{
    use HasFactory;

    protected $table = 'garantias';

    protected $fillable = [
        'asociado_id',
        'altura_mora',
        'saldo_capital',
        'valor_a_pagar',
        'tipo_garantia_id',
        'nro_escr_o_matri',
        'direccion',
        'ciudad_registro',
        'valor_avaluo',
        'fecha_avaluo',
        'bien_con_prenda',
        'bien_sin_prenda',
        'valor_avaluo_comercial',
        'observaciones'
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }

    public function tipoGarantia()
    {
        return $this->belongsTo(tipoGarantia::class);
    }

    public function tercero()
    {
        return $this->belongsTo(Tercero::class, 'tercero_garantia', 'tercero_id');
    }
}
