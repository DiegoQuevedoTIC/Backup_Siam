<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreMensualResource\Pages;
use App\Filament\Resources\CierreMensualResource\Widgets\ComprobanteMensualDetalleTable;
use App\Models\CierreMensual;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CierreMensualResource extends Resource
{
    protected static ?string $model = CierreMensual::class;

    protected static ?string    $navigationIcon = 'heroicon-o-swatch';
    protected static ?string    $navigationLabel = 'Cierre mensual';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Procesos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make()
                    ->schema([
                        DatePicker::make('fecha_cierre')
                            ->label('Fecha Cierre')
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d')
                            ->native(false),
                        Select::make('mes_cierre')
                            ->label('Mes Cierre')
                            ->searchable()
                            ->native(false)
                            ->options([
                                'Enero',
                                'Febrero',
                                'Marzo',
                                'Abril',
                                'Mayo',
                                'Junio',
                                'Julio',
                                'Agosto',
                                'Septiembre',
                                'Octubre',
                                'Noviembre',
                                'Diciembre'
                            ]),
                    ])
                    ->label('Datos principales')
                    ->description('Seleccione el mes y del la fecha de cierre, recuerde que un mes cerrado no puede cerrarse de nuevo')
                    ->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('mes_cierre')
                ->label('Mes Cierre')
                ->searchable(),
                TextColumn::make('fecha_cierre')
                ->label('Fecha Cierre')
                ->searchable()
                ->sortable()
                ->date('d/m/Y')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver Cierre')
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCierreMensuals::route('/'),
            'create' => Pages\CreateCierreMensual::route('/create'),
            'edit' => Pages\EditCierreMensual::route('/{record}/edit'),
            'view' => Pages\ViewCierreMensual::route('/{record}/view')
        ];
    }

    public static function getWidgets(): array
    {
        return  [
            ComprobanteMensualDetalleTable::class
        ];
    }
}
