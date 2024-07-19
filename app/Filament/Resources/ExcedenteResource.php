<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExcedenteResource\Pages;
use App\Filament\Resources\ExcedenteResource\RelationManagers;
use App\Models\Excedente;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExcedenteResource extends Resource
{
    protected static ?string $model = Excedente::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Excedentes / PyG';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Informes';

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
                ]),
                DatePicker::make('fecha_desde')
                ->label('Fecha inicial')
                ->native(false)
                ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_hasta')
                ->label('Fecha Final')
                ->native(false)
                ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_comparacion_desde')
                ->label('Fecha inicial')
                ->native(false)
                ->displayFormat('d/m/Y'),
                DatePicker::make('fecha_comparacion_hasta')
                ->label('Fecha Final')
                ->native(false)
                ->displayFormat('d/m/Y'),
                Toggle::make('is_mes_13')->label('¿Incluye Mes Trece?'),
                Toggle::make('is_subcentro')->label('Subcentro')
            ]);
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\CreateExcedente::route('/')
            /*'index' => Pages\ListExcedentes::route('/'),
            'create' => Pages\CreateExcedente::route('/create'),
            'edit' => Pages\EditExcedente::route('/{record}/edit'),*/
        ];
    }
}
