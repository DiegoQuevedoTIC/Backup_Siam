<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrincipalCreditoCuota extends Model
{
    use HasFactory;
    protected $table = 'principal_credito_coutas';

    protected $fillable = [
        'credito_solicitud_id',
        'periodo',
        'vlr_cuota',
        'vlr_interes',
        'amortizacion_capital',
        'saldo',
    ];

    public function creditoSolicitud()
    {
        return $this->belongsTo(CreditoSolicitud::class);
    }
}
