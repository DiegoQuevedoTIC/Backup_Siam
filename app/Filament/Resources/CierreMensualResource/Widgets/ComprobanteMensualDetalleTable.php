<?php

namespace App\Filament\Resources\CierreMensualResource\Widgets;

use App\Models\CierreMensualDetalle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ComprobanteMensualDetalleTable extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;
    
    public function table(Table $table): Table
    {

        return $table
            ->query(CierreMensualDetalle::query())
            ->columns([
                TextColumn::make('puc.puc')->label('PUC'),
                TextColumn::make('saldo_anterior')->label('Saldo Anterior'),
                TextColumn::make('debito')->label('Débito'),
                TextColumn::make('credito')->label('Crédito'),
                TextColumn::make('saldo_actual')->label('Saldo Actual'),
            ]);
    }
}
