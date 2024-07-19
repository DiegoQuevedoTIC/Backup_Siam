<?php

namespace App\Filament\Resources\Reportes;

use App\Filament\Resources\Reportes\AnexosTributariosResource\Pages;
use App\Filament\Resources\Reportes\AnexosTributariosResource\RelationManagers;
use App\Models\AnexosTributarios;
use App\Models\Puc;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnexosTributariosResource extends Resource
{
    protected static ?string $model = AnexosTributarios::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Anexos Tributarios';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Informes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('fecha_inicial')->label('Fecha Inicial')->native(false)->format('d/m/Y'),
                DatePicker::make('fecha_final')->label('Fecha Final')->native(false)->format('d/m/Y'),
                Select::make('cuenta_inicial')
                ->native(false)
                ->searchable()
                ->options(Puc::all(['id','puc'])->pluck('puc', 'id')->toArray()),
                Select::make('cuenta_final')
                ->native(false)
                ->searchable()
                ->options(Puc::all(['id','puc'])->pluck('puc', 'id')->toArray()),
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
            'index' => Pages\CreateAnexosTributarios::route('/')
            //'index' => Pages\ListAnexosTributarios::route('/'),
            //'create' => Pages\CreateAnexosTributarios::route('/create'),
            //'edit' => Pages\EditAnexosTributarios::route('/{record}/edit'),
        ];
    }
}
