<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesContabilidad;
use App\Filament\Resources\AuxiliarATerceroResource\Pages;
use App\Filament\Resources\AuxiliarATerceroResource\RelationManagers;
use App\Models\AuxiliarATercero;
use App\Models\Puc;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuxiliarATerceroResource extends Resource
{
    protected static ?string    $model = AuxiliarATercero::class;
    protected static ?string    $cluster = InformesContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Auxiliares';
    protected static ?string    $modelLabel = 'Auxiliares';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_auxiliar')
                    ->label('Tipo Auxiliar')
                    ->options([
                        '1' => 'Auxiliar a tercero',
                        '2' => 'Auxiliar a cuenta',
                        '3' => 'Auxiliar detalle cuenta',
                        '4' => 'Auxiliar tipo de documento',
                    ])
                    ->required()
                    ->live()
                    ->searchable(),
                Select::make('tercero_id')
                    ->label('Tercero')
                    ->native(false)
                    ->searchable()
                    ->visible(function (Get $get) {
                        $tipo_auxiliar = $get('tipo_auxiliar');

                        if ($tipo_auxiliar == '1') {
                            return true;
                        }
                        return false;
                    })
                    ->required()
                    ->options(Tercero::all()->pluck('nombres', 'id')->toArray()),
                DatePicker::make('fecha_inicial')->label('Fecha Inicial')->format('d/m/Y')->native(false)->required(),
                DatePicker::make('fecha_final')->label('Fecha Final')->format('d/m/Y')->native(false)->required(),
                Select::make('cuenta_inicial')
                    ->native(false)
                    ->searchable()
                    ->options(Puc::all(['id', 'puc'])->pluck('puc', 'id')->toArray()),
                Select::make('cuenta_final')
                    ->native(false)
                    ->searchable()
                    ->options(Puc::all(['id', 'puc'])->pluck('puc', 'id')->toArray()),
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
            'index' => Pages\ListAuxiliarATerceros::route('/'),
            'create' => Pages\CreateAuxiliarATercero::route('/create'),
            'edit' => Pages\EditAuxiliarATercero::route('/{record}/edit'),
        ];
    }
}
