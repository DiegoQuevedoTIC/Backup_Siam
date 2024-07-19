<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;

class ObligacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'obligaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Concepto Obligación')
                    ->description('Definir texto de ejemplo')
                    ->schema([
                        // ...
                        Forms\Components\TextInput::make('aportes')->required(),
                        Forms\Components\TextInput::make('valor_descuento')->required()->numeric(),
                        Forms\Components\Checkbox::make('plazo')->label('Plazo')->required(),
                        Forms\Components\DatePicker::make('fecha_inicio_descuento'),
                        Forms\Components\TextInput::make('periodo_descuento'),
                    ])->columns(3),
                Section::make('Historico Obligación')
                    ->description('Definir texto de ejemplo')
                    ->schema([
                        // ...
                        Forms\Components\TextInput::make('concepto')->required(),
                        Forms\Components\TextInput::make('nro_cuota')->numeric(),
                        Forms\Components\Checkbox::make('vigente'),
                        Forms\Components\Checkbox::make('vencida'),
                        Forms\Components\DatePicker::make('fecha_ultima_couta'),
                    ])->columns(4),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('concepto')
            ->columns([
                Tables\Columns\TextColumn::make('concepto')->label('Concepto Obligación')->default('N/A'),
                Tables\Columns\TextColumn::make('valor_descuento')->label('Valor Obligación')->default('N/A'),
                Tables\Columns\TextColumn::make('fecha_limite_pago')->label('Fecha limite Pago')->default('N/A'),
                Tables\Columns\TextColumn::make('nro_cuota')->label('Nro de Cuota')->default('N/A'),
                Tables\Columns\TextColumn::make('limite_cuotas')->label('Limite de Cuotas')->default('N/A'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Programar Obligación'),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
               /*  Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */
            ]);
    }
}
