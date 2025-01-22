<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ConsultasContabilidad;
use App\Filament\Resources\ConsultaComprobanteResource\Pages;
use App\Filament\Resources\ConsultaComprobanteResource\RelationManagers;
use App\Models\ConsultaComprobante;
use App\Models\TipoDocumentoContable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ConsultaComprobanteResource extends Resource
{
    protected static ?string    $model = ConsultaComprobante::class;
    protected static ?string    $cluster = ConsultasContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Comprobantes Movimiento';
    protected static ?string    $modelLabel = 'Comprobantes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_documento')
                    ->options(TipoDocumentoContable::all()->pluck('tipo_documento', 'id'))
                    ->required()
                    ->searchable()
                    ->placeholder('Seleccione el tipo de documento'),
                TextInput::make('n_documento')
                    ->autocomplete(false)
                    ->label('Nro de comprobante'),
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
            'index' => Pages\CreateConsultaComprobante::route('/create'),
            'create' => Pages\CreateConsultaComprobante::route('/'),
            'edit' => Pages\EditConsultaComprobante::route('/{record}/edit'),
        ];
    }
}
