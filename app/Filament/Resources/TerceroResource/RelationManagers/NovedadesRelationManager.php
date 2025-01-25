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
                ->autocomplete(false)
                ->label('Observaciones')
                ->markAsRequired(false)
                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                    $set('ultimo_grado', ucwords(strtolower($state)));
                })
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
                ->label('Id Novedad'),
                Tables\Columns\TextColumn::make('fecha_novedad')
                ->label('Fecha'),
                Tables\Columns\TextColumn::make('novedad.nombre')
                ->label('Novedad Aplicada'),
                Tables\Columns\TextColumn::make('observaciones')
                ->label('Observaciones'),
            ])

            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('+ Agregar Nueva Novedad'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                ->color('warning')
                ->icon('heroicon-o-eye')
                ->label('Ver'),
            ])

            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                ->label('Gestionar Información'),
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Agregar Novedad')
            ->emptyStateDescription('En este módulo podrás gestionar de forma sencilla las novedades.');

    }
}
