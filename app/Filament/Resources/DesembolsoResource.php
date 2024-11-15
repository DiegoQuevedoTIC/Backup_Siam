<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Tesoreria;
use App\Filament\Resources\DesembolsoResource\Pages;
use App\Filament\Resources\DesembolsoResource\RelationManagers;
use App\Models\Desembolso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesembolsoResource extends Resource
{
    protected static ?string $model = Desembolso::class;
    protected static ?string $cluster = Tesoreria::class;
    protected static ?string $modelLabel = 'Desembolso';
    protected static ?string $navigationIcon = 'heroicon-o-folder-minus';
    protected static ?string $navigationLabel = 'Desembolsos';
    protected static ?int $navigationSort = -1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListDesembolsos::route('/'),
            'create' => Pages\CreateDesembolso::route('/create'),
            'view' => Pages\ViewDesembolso::route('/{record}'),
            'edit' => Pages\EditDesembolso::route('/{record}/edit'),
        ];
    }
}
