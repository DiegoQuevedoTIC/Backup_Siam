<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ConsultasContabilidad;
use App\Filament\Resources\SaldoConciliadoResource\Pages;
use App\Filament\Resources\SaldoConciliadoResource\RelationManagers;
use App\Models\SaldoConciliado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SaldoConciliadoResource extends Resource
{
    protected static ?string    $model = SaldoConciliado::class;
    protected static ?string    $cluster = ConsultasContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Saldo Conciliados';
    protected static ?string    $modelLabel = 'Saldo Conciliados';

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
            'index' => Pages\ListSaldoConciliados::route('/'),
            'create' => Pages\CreateSaldoConciliado::route('/create'),
            'edit' => Pages\EditSaldoConciliado::route('/{record}/edit'),
        ];
    }
}
