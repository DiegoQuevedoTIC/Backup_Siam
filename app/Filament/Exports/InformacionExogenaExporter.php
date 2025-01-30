<?php

namespace App\Filament\Exports;

use App\Models\InformacionExogena;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InformacionExogenaExporter extends Exporter
{
    protected static ?string $model = InformacionExogena::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('fecha_inicial')->label('Fecha Inicial'),
            ExportColumn::make('fecha_final')->label('Fecha Final'),
            ExportColumn::make('Tipo_Informe')->label('Tipo de Informe'),
            ExportColumn::make('created_at')->label('Created At'),
            ExportColumn::make('updated_at')->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your informacion exogena export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
