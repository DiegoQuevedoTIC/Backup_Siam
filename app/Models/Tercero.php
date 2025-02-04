<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Tercero extends Model
{
    use HasFactory, SoftDeletes;

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
        'ruta_imagen',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];


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

    public function Patrimonio(): HasOne
    {
        return $this->hasOne(Patrimonio::class);
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

    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->primer_apellido . ' ' . $this->segundo_apellido;
    }

    public function carteraEncabezados()
    {
        return $this->hasMany(CarteraEncabezado::class, 'cliente', 'tercero_id')->orderBy('nro_docto', 'asc');
    }

    public function obligaciones()
    {
        return $this->hasMany(Obligacion::class, 'cliente', 'tercero_id')
            ->join('asociados as a', 'detalle_vencimiento_descuento.cliente', '=', DB::raw('CAST(a.codigo_interno_pag AS INTEGER)'))
            ->join('concepto_descuentos as c', 'detalle_vencimiento_descuento.con_descuento', '=', 'c.codigo_descuento')
            ->where('detalle_vencimiento_descuento.estado', 'A')
            ->orderBy('detalle_vencimiento_descuento.fecha_vencimiento', 'ASC')
            ->orderBy('detalle_vencimiento_descuento.con_descuento', 'ASC')
            ->select(
                'detalle_vencimiento_descuento.id',
                'detalle_vencimiento_descuento.fecha_vencimiento',
                'detalle_vencimiento_descuento.con_descuento',
                'c.descripcion as descripcion_concepto',
                'detalle_vencimiento_descuento.consecutivo',
                'detalle_vencimiento_descuento.nro_cuota',
                'detalle_vencimiento_descuento.vlr_cuota',
                'detalle_vencimiento_descuento.vlr_congelada'
            );
    }

    public function vencimientoDescuentos()
    {
        return $this->hasMany(VencimientoDescuento::class, 'cliente', 'tercero_id');
    }
}
