<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aporte extends Model
{
    use HasFactory;

    protected $table = 'saldos_descuentos';

    protected $fillable = [
        'asociado_id',
        'concepto',
        'movimientos_debito',
        'movimientos_credito',
        'saldo'
    ];

    public function asociado()
    {
        return $this->belongsTo(Asociado::class);
    }
}
