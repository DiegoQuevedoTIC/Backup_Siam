<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosContabilidad;
use App\Filament\Resources\FirmaResource\Pages;
use App\Filament\Resources\FirmaResource\RelationManagers;
use App\Models\Firma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FirmaResource extends Resource
{
    protected static ?string $model = Firma::class;
    protected static ?string $cluster = ParametrosContabilidad::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('representante_legal')->required(),
                Forms\Components\TextInput::make('ci_representante_legal')->required(),
                Forms\Components\TextInput::make('matricula_representante_legal')->required(),
                Forms\Components\TextInput::make('revisor_fiscal')->required(),
                Forms\Components\TextInput::make('ci_revisor_fiscal')->required(),
                Forms\Components\TextInput::make('matricula_revisor_fiscal')->required(),
                Forms\Components\TextInput::make('contador')->required(),
                Forms\Components\TextInput::make('ci_contador')->required(),
                Forms\Components\TextInput::make('matricula_contador')->required(),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('representante_legal'),
                Tables\Columns\TextColumn::make('revisor_fiscal'),
                Tables\Columns\TextColumn::make('contador'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFirmas::route('/'),
        ];
    }
}
