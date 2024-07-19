<?php

namespace App\Filament\Resources\Reportes\auxiliares;

use App\Filament\Resources\Reportes\auxiliares\AuxiliarATerceroResource\Pages;
use App\Filament\Resources\Reportes\auxiliares\AuxiliarATerceroResource\RelationManagers;
use App\Models\AuxiliarATercero;
use App\Models\Puc;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuxiliarATerceroResource extends Resource
{
    protected static ?string $model = AuxiliarATercero::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Auxiliar A Tercero';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Informes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tercero_id')
                    ->native(false)
                    ->searchable()
                    ->options(Tercero::all()->pluck('nombres', 'id')->toArray()),
                Section::make('rango_fecha')->label('Rango de fechas')
                    ->schema([
                        DatePicker::make('fecha_inicial')->label('Fecha Inicial')->format('d/m/Y')->native(false),
                        DatePicker::make('fecha_final')->label('Fecha Final')->format('d/m/Y')->native(false)
                    ]),
                Section::make('rango_cuentas')->label('Rango de cuentas')
                    ->schema([
                        Select::make('cuenta_inicial')
                            ->native(false)
                            ->searchable()
                            ->options(Puc::all(['id', 'puc'])->pluck('puc', 'id')->toArray()),
                        Select::make('cuenta_final')
                            ->native(false)
                            ->searchable()
                            ->options(Puc::all(['id', 'puc'])->pluck('puc', 'id')->toArray()),
                    ])
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
            'index' => Pages\CreateAuxiliarATercero::route('/')
        ];
    }
}
