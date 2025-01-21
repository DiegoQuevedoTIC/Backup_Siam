<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Tesoreria;
use App\Filament\Resources\PagoColectivoResource\Pages;
use App\Filament\Resources\PagoColectivoResource\RelationManagers;
use App\Models\PagoColectivo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagoColectivoResource extends Resource
{
    protected static ?string $model = PagoColectivo::class;
    protected static ?string $cluster = Tesoreria::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Pago Colectivo';
    protected static ?string $modelLabel = 'Pago Colectivo';

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
            'index' => Pages\ListPagoColectivos::route('/'),
            'create' => Pages\CreatePagoColectivo::route('/create'),
            'view' => Pages\ViewPagoColectivo::route('/{record}'),
            'edit' => Pages\EditPagoColectivo::route('/{record}/edit'),
        ];
    }
}
