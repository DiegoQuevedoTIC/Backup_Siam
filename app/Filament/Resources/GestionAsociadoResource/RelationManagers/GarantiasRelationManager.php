<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\Tercero;
use App\Models\TipoGarantia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class GarantiasRelationManager extends RelationManager
{
    protected static string $relationship = 'garantias';

    public function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('tipo_garantia_id')
                ->label('Tipo de garantía')
                ->options([
                    'R' => 'Garantia Real',
                    'P' => 'Garantia Personal'
                ])
                ->searchable()
                ->required()
                ->reactive(),
            Forms\Components\TextInput::make('nro_escr_o_matri')
                ->label('Nro escritura / Matrícula')
                ->required()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\TextInput::make('direccion')
                ->label('Dirección')
                ->required()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\TextInput::make('ciudad_registro')
                ->label('Ciudad Registro')
                ->required()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\TextInput::make('valor_avaluo')
                ->label('Valor Avaluo')
                ->required()
                ->numeric()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\DatePicker::make('fecha_avaluo')
                ->label('Fecha Avaluo')
                ->required()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\Checkbox::make('bien_con_prenda')
                ->label('Bien con prenda')
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\Checkbox::make('bien_sin_prenda')
                ->label('Bien sin prenda')
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            Forms\Components\TextInput::make('valor_avaluo_comercial')
                ->label('Valor Avaluo Comercial')
                ->required()
                ->numeric()
                ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'R'),

            // Campos para garantía "personal"
            Forms\Components\Select::make('tercero_asesor')
            ->label('Codigo Asesor')
            ->searchable()
            ->visible(fn (callable $get) => $get('tipo_garantia_id') === 'P')
            ->options(fn() => Tercero::query()
                ->select(DB::raw("id, CONCAT(nombres, ' ', primer_apellido) AS nombre_completo"))
                ->pluck('nombre_completo', 'id'))
            ->required(),


            // Campos comunes
            Forms\Components\TextInput::make('observaciones')
            ->label('Observaciones')
            ->required()
            ->maxLength(65535)
            ->columnSpanFull(),

        ]);
}


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('altura_mora')
            ->columns([
                Tables\Columns\TextColumn::make('linea_credito')->label('Linea Credito')->default('N/A'),
                Tables\Columns\TextColumn::make('nro_credito')->label('Nro Credito')->default('N/A'),
                Tables\Columns\TextColumn::make('altura_mora')->label('Altura Mora')->default('N/A'),
                Tables\Columns\TextColumn::make('valor_avaluo')->label('Saldo Capital'),
                Tables\Columns\TextColumn::make('valor_a_pagar')->label('Valor a Pagar'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Crear Garantia'),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ]);
    }
}
