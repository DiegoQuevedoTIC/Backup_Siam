<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AportesRelationManager extends RelationManager
{
    protected static string $relationship = 'aportes';

    protected static ?string $title = 'Aportes - Ahorros';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('concepto')
                    ->required()
                    ->autocomplete(false)
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('concepto')
            ->columns([
                Tables\Columns\TextColumn::make('con_descuento'),
                Tables\Columns\TextColumn::make('descripcion'),
                Tables\Columns\TextColumn::make('saldo_debito')->money('COP'),
                Tables\Columns\TextColumn::make('saldo_credito')->money('COP'),
                Tables\Columns\TextColumn::make('saldo_total')->money('COP'),
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

