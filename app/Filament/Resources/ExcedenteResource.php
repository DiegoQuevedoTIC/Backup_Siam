<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesContabilidad;
use App\Filament\Resources\ExcedenteResource\Pages;
use App\Filament\Resources\ExcedenteResource\RelationManagers;
use App\Models\Excedente;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExcedenteResource extends Resource
{
    protected static ?string    $model = Excedente::class;
    protected static ?string    $cluster = InformesContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Excedentes / PyG';
    protected static ?string    $modelLabel = 'Excedentes / PyG';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_informe')
                    ->label('Tipo Informe')
                    ->options([
                        '1' => 'Formato Standard',
                        '2' => 'Formato Detallado',
                        '3' => 'Comparativo'
                    ])->searchable()->live(),
                DatePicker::make('fecha_desde')
                    ->label('Fecha inicial')
                    ->native(false)
                    ->required()
                    ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_hasta')
                    ->label('Fecha Final')
                    ->native(false)
                    ->required()
                    ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_comparacion_desde')
                    ->label('Fecha inicial')
                    ->native(false)
                    ->visible(function (Get $get) {
                        $tipo_informe = $get('tipo_informe');

                        if ($tipo_informe == '3') {
                            return true;
                        }

                        return false;
                    })
                    ->required(function (Get $get) {
                        $tipo_informe = $get('tipo_informe');

                        if ($tipo_informe == '3') {
                            return true;
                        }

                        return false;
                    })
                    ->live()
                    ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_comparacion_hasta')
                    ->label('Fecha Final')
                    ->native(false)
                    ->visible(function (Get $get) {
                        $tipo_informe = $get('tipo_informe');

                        if ($tipo_informe == '3') {
                            return true;
                        }

                        return false;
                    })
                    ->required(function (Get $get) {
                        $tipo_informe = $get('tipo_informe');

                        if ($tipo_informe == '3') {
                            return true;
                        }

                        return false;
                    })
                    ->displayFormat('d/m/Y'),
                Toggle::make('is_mes_13')->label('¿Incluye Mes Trece?')->visible(false),
                Toggle::make('is_subcentro')->label('Subcentro')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListExcedentes::route('/'),
            'create' => Pages\CreateExcedente::route('/create'),
            'edit' => Pages\EditExcedente::route('/{record}/edit'),
        ];
    }
}
