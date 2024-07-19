<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->heading('Historico Gestiones de Cobranza')
            ->recordTitleAttribute('fecha_gestion')
            ->columns([
                Tables\Columns\TextColumn::make('fecha_gestion'),
                Tables\Columns\TextColumn::make('nro_producto'),
                Tables\Columns\TextColumn::make('tipo_gestion'),
                Tables\Columns\TextColumn::make('detalles_gestion'),
                Tables\Columns\TextColumn::make('resultado'),
                Tables\Columns\TextColumn::make('usuario_gestion'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
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
