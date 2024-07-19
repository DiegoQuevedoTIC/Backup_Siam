<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tercero extends Model
{
    use HasFactory,
    SoftDeletes;


    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }



    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class);
    }


    public function nivelescolar(): BelongsTo
    {
        return $this->belongsTo(NivelEscolar::class);
    }

    public function estadocivil(): BelongsTo
    {
        return $this->belongsTo(EstadoCivil::class);
    }

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class);
    }

    public function barrio(): BelongsTo
    {
        return $this->belongsTo(Barrio::class);
    }



    public function TipoContribuyente(): BelongsTo
    {
        return $this->belongsTo(TipoContribuyente::class);
    }



    public function TipoIdentificacion(): BelongsTo
    {
        return $this->belongsTo(TipoIdentificacion::class);
    }



    public function TerceroSarlaft(): HasOne
    {
        return $this->hasOne(TerceroSarlaft::class);
    }



    public function InformacionFinanciera(): HasOne
    {
        return $this->hasOne(InformacionFinanciera::class);
    }



    public function Referencias(): HasOne
    {
        return $this->hasOne(Referencias::class);
    }




    public function Novedades(): HasOne
    {
        return $this->hasOne(Novedades::class);
    }



    public function asociado(): HasOne
    {
        return $this->hasOne(Asociado::class);
    }

    public function comprobantes(): HasMany
    {
        return $this->hasMany(Comprobante::class);
    }

    public function comprobantesLineas(): HasMany
    {
        return $this->hasMany(ComprobanteLinea::class);
    }



    protected $fillable = [
        'tercero_id',
        'digito_verificacion',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'direccion',
        'telefono',
        'celular',
        'email',
        'tipo_tercero',
        'pais_id',
        'ciudad_id',
        'barrio_id',
        'tipo_contribuyente_id',
        'ocupacion',
        'nivelescolar_id',
        'estadocivil_id',
        'observaciones',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];


}
