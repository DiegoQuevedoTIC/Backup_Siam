<?php

namespace App\Filament\Exports;

use App\Models\Cdatinfo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CdatinfoExporter extends Exporter
{
    protected static ?string $model = Cdatinfo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('numero_titulo'),
            ExportColumn::make('cliente'),
            ExportColumn::make('nombre'),
            ExportColumn::make('plazo'),
            ExportColumn::make('tasa_remuneracion'),
            ExportColumn::make('fecha_titulo'),
            ExportColumn::make('fecha_vencimiento'),
            ExportColumn::make('saldo_actual'),
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
