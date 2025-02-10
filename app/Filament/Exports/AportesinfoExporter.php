<?php

namespace App\Filament\Exports;

use App\Models\Aportesinfo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AportesinfoExporter extends Exporter
{
    protected static ?string $model = Aportesinfo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('cliente'),
            ExportColumn::make('nombre'),
            ExportColumn::make('concepto'),
            ExportColumn::make('descripcion'),
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
