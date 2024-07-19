<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LibroOficialResource\Pages;
use App\Filament\Resources\LibroOficialResource\RelationManagers;
use App\Models\LibroOficial;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LibroOficialResource extends Resource
{
    protected static ?string $model = LibroOficial::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Libros Oficiales';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Informes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo_libro')
                    ->label('Tipo de Libro Oficial')
                    ->options( [
                        1 => "Libro Mayor y Balances",
                        2 => "Libro Diario Columnario",
                        3 => "Libro de Socios",
                        4 => "Inventario y Balance"
                    ]),
                Section::make('rango_fecha')->label('Rango de Fecha')->schema([
                    DatePicker::make('fecha_inicial')->format('d/m/Y')->native(false),
                    DatePicker::make('fecha_final')->format('d/m/Y')->native(false),
                ])->columnSpan(2),
                Toggle::make('is_mes_trece')->label('¿Incluye Mes Trece?'),
                Toggle::make('is_libro_oficial')->label('Libro Oficial')

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\CreateLibroOficial::route('/')
            //'index' => Pages\ListLibroOficials::route('/'),
            //'create' => Pages\CreateLibroOficial::route('/create'),
            //'edit' => Pages\EditLibroOficial::route('/{record}/edit'),
        ];
    }
}
