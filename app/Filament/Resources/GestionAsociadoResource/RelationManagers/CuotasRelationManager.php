<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuotasRelationManager extends RelationManager
{
    protected static string $relationship = 'cuotas';
    protected static ?string $title = 'Cartera';
    protected static bool $isLazy = false;

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-m-cog';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cliente')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', 'A'))
            ->recordTitleAttribute('cliente')
            ->columns([
                Tables\Columns\TextColumn::make('nro_docto'),
                Tables\Columns\TextColumn::make('cliente'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Gestionar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
