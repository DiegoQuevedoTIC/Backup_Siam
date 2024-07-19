<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Tercero;
use App\Models\NovedadTercero;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

class NovedadesRelationManager extends RelationManager
{
    protected static string $relationship = 'Novedades';

    public function form(Form $form): Form
    {
        return $form
            
            ->schema([
                Select::make('novedad_id') 
                ->relationship('novedad', 'nombre')
                ->required()
                ->placeholder('')
                ->markAsRequired(false)
                ->preload()
                ->label('Novedad'),
                DatePicker::make('fecha_novedad')
                ->markAsRequired(false)
                ->required()
                ->label('Fecha Novedad'),
                Textarea::make('observaciones')
                ->maxLength(65535)
                ->markAsRequired(false)
                ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Noas')
            ->columns([
                Tables\Columns\TextColumn::make('novedad_id')
                ->label('No')
                
                ->sortable()
                ,
                Tables\Columns\TextColumn::make('fecha_novedad')
                ->label('Fecha'),
                Tables\Columns\TextColumn::make('novedad_tercero.nombre')
                ->label('Novedad Aplicada'),
                Tables\Columns\TextColumn::make('observaciones')
                ->label('Observaciones'),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
