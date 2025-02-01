<?php

namespace App\Filament\Exports;

use App\Models\Exogena1022;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class Informe1022Exporter extends Exporter
{
    protected static ?string $model = Exogena1022::class;

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
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your informe1022 export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
