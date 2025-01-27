<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesCumplimiento;
use App\Filament\Resources\CentralRiesgoResource\Pages;
use App\Filament\Resources\CentralRiesgoResource\RelationManagers;
use App\Models\CentralRiesgo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CentralRiesgoResource extends Resource
{
    protected static ?string $model = CentralRiesgo::class;
    protected static ?string $cluster = InformesCumplimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Informes Centrales de Riesgo';
    protected static ?string $modelLabel = 'Informes Centrales de Riesgo';

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
            ->emptyStateHeading('Informe Centrales de Riesgo')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla los archivos de reporte para envio a las diferentes centrales de Riesgo.')

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
            'index' => Pages\ListCentralRiesgos::route('/'),
            'create' => Pages\CreateCentralRiesgo::route('/create'),
            'edit' => Pages\EditCentralRiesgo::route('/{record}/edit'),
        ];
    }


}
