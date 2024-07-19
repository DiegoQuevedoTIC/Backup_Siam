<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;



class Asociado extends Model

{
    use HasFactory;
    protected $table = 'asociados';

    public function pagaduria(): BelongsTo
    {
        return $this->belongsTo(Pagaduria::class);
    }
    public function EstadoCliente(): BelongsTo
    {
        return $this->belongsTo(EstadoCliente::class);
    }
    public function banco(): BelongsTo
    {
        return $this->belongsTo(Banco::class);
    }
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }
    public function tiporesidencia(): BelongsTo
    {
        return $this->belongsTo(TipoResidencia::class);
    }
    public function estadocivil(): BelongsTo
    {
        return $this->belongsTo(EstadoCivil::class);
    }
    public function parentesco(): BelongsTo
    {
        return $this->belongsTo(Parentesco::class);
    }
    public function nivelescolar(): BelongsTo
    {
        return $this->belongsTo(NivelEscolar::class);
    }
    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class);
    }
    public function actividadeconomica(): BelongsTo
    {
        return $this->belongsTo(ActividadEconomica::class);
    }
    public function tipocontrato(): BelongsTo
    {
        return $this->belongsTo(TipoContrato::class);
    }
    public function tercero(): BelongsTo
    {
        return $this->BelongsTo(Tercero::class);
    }

    public function creditoSolicitudes()
    {
        return $this->hasMany(CreditoSolicitud::class);
    }

    public function obligaciones()
    {
        return $this->hasMany(Obligacion::class);
    }

    public function aportes()
    {
        return $this->hasMany(Aporte::class);
    }

    public function certificadoDepositos()
    {
        return $this->hasMany(CertificadoDeposito::class);
    }

    public function garantias()
    {
        return $this->hasMany(Garantia::class);
    }

    public function cobranzas()
    {
        return $this->hasMany(Cobranza::class);
    }

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class);
    }

    public function prorrogas()
    {
        return $this->hasMany(Prorroga::class);
    }

    protected $fillable = [];
}
