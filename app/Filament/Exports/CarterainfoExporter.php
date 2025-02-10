<?php

namespace App\Filament\Exports;

use App\Models\Carterainfo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CarterainfoExporter extends Exporter
{
    protected static ?string $model = Carterainfo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('numero_documento'),
            ExportColumn::make('cliente'),
            ExportColumn::make('nombre'),
            ExportColumn::make('linea'),
            ExportColumn::make('linea_credito'),
            ExportColumn::make('tasa_interes'),
            ExportColumn::make('fecha_documento'),
            ExportColumn::make('valor_inicial_credito'),
            ExportColumn::make('valor_cuota'),
            ExportColumn::make('saldo_actual'),
            ExportColumn::make('numero_cuotas'),
            ExportColumn::make('categoria'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your carterainfo export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
