<?php

namespace App\Filament\Exports;

use App\Models\Exogena1007;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class Informe1007Exporter extends Exporter
{
    protected static ?string $model = Exogena1007::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('concepto'),
            ExportColumn::make('tipo_documento'),
            ExportColumn::make('numero_identificacion'),
            ExportColumn::make('digitoverificacion'),
            ExportColumn::make('primer_apellido'),
            ExportColumn::make('segundo_apellido'),
            ExportColumn::make('primer_nombre'),
            ExportColumn::make('otros_nombres'),
            ExportColumn::make('razon_social'),
            ExportColumn::make('direccion'),
            ExportColumn::make('codigo_departamento'),
            ExportColumn::make('codigo_municipio'),
            ExportColumn::make('pais_residencia'),
            ExportColumn::make('valor'),
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
