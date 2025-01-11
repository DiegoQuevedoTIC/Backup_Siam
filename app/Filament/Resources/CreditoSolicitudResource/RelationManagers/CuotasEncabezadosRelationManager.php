<?php

namespace App\Filament\Resources\CreditoSolicitudResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuotasEncabezadosRelationManager extends RelationManager
{
    protected static string $relationship = 'cuotasEncabezados';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nro_cuota')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nro_cuota')
            ->columns([
                Tables\Columns\TextColumn::make('nro_cuota'),
                Tables\Columns\TextColumn::make('fecha_vencimento'),
                Tables\Columns\TextColumn::make('saldo_capital'),
                Tables\Columns\TextColumn::make('vlr_cuota'),
                Tables\Columns\TextColumn::make('vlr_interes'),
                Tables\Columns\TextColumn::make('vlr_mora'),
                Tables\Columns\TextColumn::make('vlr_total_pagar'),
                Tables\Columns\TextColumn::make('vlr_pagado'),
                Tables\Columns\TextColumn::make('nro_cuota'),
                Tables\Columns\TextColumn::make('created_at'),
                Tables\Columns\TextColumn::make('updated_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
