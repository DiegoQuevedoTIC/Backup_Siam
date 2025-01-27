<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesCumplimiento;
use App\Filament\Resources\InformacionExogenaResource\Pages;
use App\Filament\Resources\InformacionExogenaResource\RelationManagers;
use App\Models\InformacionExogena;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InformacionExogenaResource extends Resource
{
    protected static ?string $model = InformacionExogena::class;
    protected static?string $cluster = InformesCumplimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static?string $navigationLabel = 'Información Exógena';
    protected static?string $modelLabel = 'Información Exógena';

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

            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                ->label('Generar Informe'),
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Informacion Exogena')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla los distintos archivos de informacion Exogena para reportar a la direccion de impuestos.')


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
            'index' => Pages\ListInformacionExogenas::route('/'),
            'create' => Pages\CreateInformacionExogena::route('/create'),
            'edit' => Pages\EditInformacionExogena::route('/{record}/edit'),
        ];
    }
}
