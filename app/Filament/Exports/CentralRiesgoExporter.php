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
            ExportColumn::make('tdocto'),
            ExportColumn::make('nro_docto'),
            ExportColumn::make('cliente'),
            ExportColumn::make('linea'),
            ExportColumn::make('estado'),
            ExportColumn::make('fecha_docto'),
            ExportColumn::make('fecha_primer_vto'),
            ExportColumn::make('vlr_docto_vto'),
            ExportColumn::make('vlr_saldo_actual'),
            ExportColumn::make('fecha_corte'),
        ];
    }




    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your central riesgo export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
