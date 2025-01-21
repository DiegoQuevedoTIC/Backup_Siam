<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(Obligacion::class, 'cliente', 'codigo_interno_pag')
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
                'detalle_vencimiento_descuento.vlr_cuota'
            );
    }

    public function aportes()
    {
        return $this->hasMany(Aporte::class, 'cliente', 'codigo_interno_pag')
            ->join('asociados as a', 'saldos_descuentos.cliente', '=', DB::raw('CAST(a.codigo_interno_pag AS INTEGER)'))
            ->join('concepto_descuentos as cd', 'saldos_descuentos.con_descuento', '=', 'cd.codigo_descuento')
            ->where('saldos_descuentos.amo', 9999)
            ->where('saldos_descuentos.mes', 99)
            ->whereIn('cd.identificador_concepto', ['AP', 'HC'])
            ->select(
                'saldos_descuentos.id',
                'saldos_descuentos.con_descuento',
                'cd.descripcion',
                'saldos_descuentos.saldo_debito',
                'saldos_descuentos.saldo_credito',
                'saldos_descuentos.saldo_total'
            );
    }
    public function certificadoDepositos()
    {
        return $this->hasMany(CertificadoDeposito::class);
    }

    public function garantias()
    {
        return $this->hasMany(Garantia::class);
    }

    public function garantiasCartera()
    {
        return $this->hasMany(GarantiaCartera::class);
    }

    public function cobranzas()
    {
        return $this->hasMany(CarteraEncabezado::class, 'cliente', 'codigo_interno_pag');
    }

    public function beneficiarios()
    {
        return $this->hasMany(Beneficiario::class);
    }

    public function prorrogas()
    {
        return $this->hasMany(Prorroga::class);
    }

    public function cuotas()
    {
        return $this->hasMany(CarteraEncabezado::class, 'cliente', 'codigo_interno_pag');
    }

    protected $fillable = [];
}
