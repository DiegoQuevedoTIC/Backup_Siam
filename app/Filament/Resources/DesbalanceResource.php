<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ConsultasContabilidad;
use App\Filament\Resources\DesbalanceResource\Pages;
use App\Filament\Resources\DesbalanceResource\RelationManagers;
use App\Models\Desbalance;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesbalanceResource extends Resource
{
    protected static ?string    $model = Desbalance::class;
    protected static ?string    $cluster = ConsultasContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Descuadre Comprobantes';
    protected static ?string    $modelLabel = 'Descuadre Comprobantes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_revision')
                ->label('Tipo de Revisión')
                ->options([
                    '1' => 'Debito = Credito',
                    '2' => 'Cuentas de movimiento',
                    '3' => 'Partidas con tercero',
                ])
                ->required()
                ->live()
                ->searchable()
                ->columnSpanFull(),
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
            'index' => Pages\ListDesbalances::route('/'),
            'create' => Pages\CreateDesbalance::route('/create'),
            'edit' => Pages\EditDesbalance::route('/{record}/edit'),
        ];
    }
}
