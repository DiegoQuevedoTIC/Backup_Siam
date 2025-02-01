<?php

namespace App\Filament\Exports;

use App\Models\CentralRiesgo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class CentralRiesgoExporter extends Exporter
{
    protected static ?string $model = CentralRiesgo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('tipo_identificacion_id') ->label('TIPO DE IDENTIFICACION'),
            ExportColumn::make('id_persona') ->label('NUMERO DE IDENTIFICACION'),
            ExportColumn::make('nombre_completo') ->label('NOMBRE COMPLETO'),
            ExportColumn::make('nro_docto') ->label('NUMERO DE LA CUENTA U OBLIGACION'),
            ExportColumn::make('fecha_docto') ->label('FECHA APERTURA'),
            ExportColumn::make('fecha_pago_total') ->label('FECHA VENCIMIENTO'),
            ExportColumn::make('responsable') ->label('RESPONSABLE'),
            ExportColumn::make('novedad') ->label('NOVEDAD'),
            ExportColumn::make('estado_cuenta') ->label('ESTADO ORIGEN DE LA CUENTA'),
            ExportColumn::make('vlr_docto_vto') ->label('VALOR INICIAL'),
            ExportColumn::make('vlr_saldo_actual') ->label('VALOR SALDO DEUDA'),
            ExportColumn::make('valor_disponible') ->label('VALOR DISPONIBLE'),
            ExportColumn::make('vlr_ini_cuota') ->label('V. CUOTA MENSUAL'),
            ExportColumn::make('saldo_mora') ->label('VALOR SALDO MORA'),
            ExportColumn::make('nro_cuotas') ->label('TOTAL CUOTAS'),
            ExportColumn::make('cuotas_canceladas') ->label('CUOTAS CANCELADAS'),
            ExportColumn::make('cuotas_mora') ->label('CUOTAS EN MORA'),
            ExportColumn::make('limite_pago') ->label('FECHA LIMITE DE PAGO'),
            ExportColumn::make('fecha_pago') ->label('FECHA DE PAGO'),
            ExportColumn::make('ciudad') ->label('CIUDAD CORRESPONDENCIA'),
            ExportColumn::make('direccion') ->label('DIRECCION DE CORRESPONDENCIA'),
            ExportColumn::make('email') ->label('CORREO ELECTRONICO'),
            ExportColumn::make('celular') ->label('CELULAR'),
            ExportColumn::make('situacion') ->label('SITUACION DEL TITULAR'),
            ExportColumn::make('edad_mora') ->label('EDAD DE MORA'),
            ExportColumn::make('forma_pago') ->label('FORMA DE PAGO'),
            ExportColumn::make('fecha_estado_origen') ->label('FECHA ESTADO ORIGEN'),
            ExportColumn::make('estado_de_cuenta') ->label('ESTADO DE LA CUENTA'),
            ExportColumn::make('fecha_estado_de_cuenta') ->label('FECHA ESTADO DE LA CUENTA'),
            ExportColumn::make('adjetivo') ->label('ADJETIVO'),
            ExportColumn::make('fecha_adjetivo') ->label('FECHA ADJETIVO'),
            ExportColumn::make('clausula_permanencia') ->label('CLAUSULA DE PERMANENCIA'),
            ExportColumn::make('fecha_permanencia') ->label('FECHA CLAUSULA DE PERMANENCIA'),

        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = ' Tu exportacion se ha completado y ' . number_format($export->successful_rows) . ' ' . str('filas')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('filas')->plural($failedRowsCount) . ' fallaron al exportar.';
        }

        return $body;
    }
}
