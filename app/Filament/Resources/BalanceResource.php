<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesContabilidad;
use App\Filament\Resources\BalanceResource\Pages;
use App\Filament\Resources\BalanceResource\RelationManagers;
use App\Models\Balance;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BalanceResource extends Resource
{
    protected static ?string    $model = Balance::class;
    protected static ?string    $cluster = InformesContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Balances';
    protected static ?string    $modelLabel = 'Balances Contables';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Select::make('tipo_balance')
                    ->label('Tipo Balance')
                    ->searchable()
                    ->options([
                        '1' => 'Estado Situacion Financieras (Balance General)',
                        '2' => 'Balance Horizontal',
                        '3' => 'Balance Horizontal Comparativo',
                        '4' => 'Balance por Tercero'
                    ])->required(),
                DatePicker::make('fecha_inicial')
                    ->label('Fecha Inicial')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                DatePicker::make('fecha_final')
                    ->label('Fecha Final')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                Toggle::make('is_13_month')->label('¿Incluye Mes Trece?')->inline(false),
                TextInput::make('nivel')
                ->label('Nivel')
                ->default('7')
                ->required()
                ->autocomplete(false)
                ->numeric(),

            ])
            ->columns(1);
    }

    /*public static function table(Table $table): Table
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
    }*/

    /*public static function getRelations(): array
    {
        return [
            //
        ];
    }*/

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBalances::route('/'),
            'create' => Pages\CreateBalance::route('/create'),
            'edit' => Pages\EditBalance::route('/{record}/edit'),
        ];
    }
}
