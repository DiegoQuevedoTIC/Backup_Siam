<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarteraEncabezado extends Model
{
    //
    protected $table = 'cartera_encabezados';

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $numerador = TipoDocumentoContable::where('sigla', 'PLI')->first();
            $model->nro_docto = $numerador->numerador;
            $numerador->increment('numerador');
            $numerador->save();
        });
    }


    public function asociado()
    {
        return $this->belongsTo(Asociado::class, 'cliente', 'codigo_interno_pag');
    }

    public function lineaCredito()
    {
        return $this->belongsTo(CreditoLinea::class, 'linea', 'id');
    }
}
