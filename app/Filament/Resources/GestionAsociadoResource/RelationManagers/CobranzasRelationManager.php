<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\CarteraEncabezado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;

class CobranzasRelationManager extends RelationManager
{
    protected static string $relationship = 'cobranzas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('fecha_gestion')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->where('estado', 'A')->where('tdocto', 'PAG')->where('nro_dias_mora', '>', json_decode(DB::table('general_settings')->first()->more_configs, true)['dias_cobranza'])
            )
            ->recordTitleAttribute('cliente')
            ->columns([
                Tables\Columns\TextColumn::make('nro_docto'),
                Tables\Columns\TextColumn::make('nro_cuotas')->label('Nro cuotas'),
                Tables\Columns\TextColumn::make('lineaCredito.descripcion'),
                Tables\Columns\TextColumn::make('nro_dias_mora'),
                Tables\Columns\TextColumn::make('vlr_saldo_actual')->label('Saldo actual')
                    ->money('COP'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de desembolso'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make()->label('Gestionar'),
                Action::make('gestionar')->label('Gestionar')
                    ->iconSize('md')
                    ->color('info')
                    ->icon('heroicon-s-pencil')
                    ->modalSubmitAction(false),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }
}
