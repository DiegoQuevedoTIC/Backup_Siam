<?php

namespace App\Filament\Exports;

use App\Models\Exogena1010;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class Informe1010Exporter extends Exporter
{
    protected static ?string $model = Exogena1010::class;

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
        $body = 'Your informe1010 export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
