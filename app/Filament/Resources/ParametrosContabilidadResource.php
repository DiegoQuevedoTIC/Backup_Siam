<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametrosContabilidadResource\Pages;
use App\Filament\Resources\ParametrosContabilidadResource\RelationManagers;
use App\Models\ParametrosContabilidad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ParametrosContabilidadResource extends Resource
{
    protected static ?string $model = ParametrosContabilidad::class;

    protected static ?string    $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string    $navigationLabel = 'Parametros Contabilidad';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?int       $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParametrosContabilidads::route('/'),
            'create' => Pages\CreateParametrosContabilidad::route('/create'),
            'view' => Pages\ViewParametrosContabilidad::route('/{record}'),
            'edit' => Pages\EditParametrosContabilidad::route('/{record}/edit'),
        ];
    }
}
