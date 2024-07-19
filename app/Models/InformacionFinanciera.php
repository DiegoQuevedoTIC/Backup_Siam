<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InformacionFinanciera extends Model
{
    use HasFactory;

    protected $table = 'informacion_financieras';

    protected $fillable = [
        'total_activos',
        'total_pasivos',
        'total_patrimonio',
        'salario',
        'honorarios',
        'otros_ingresos',
        'total_ingresos',
        'gastos_sostenimiento',
        'gastos_financieros',
        'creditos_hipotecarios',
        'otros_gastos',
    ];

    public function Tercero(): BelongsTo
    {
        return $this->belongsTo(Tercero::class);
    }
}
