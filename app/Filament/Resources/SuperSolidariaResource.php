<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesCumplimiento;
use App\Filament\Resources\SuperSolidariaResource\Pages;
use App\Filament\Resources\SuperSolidariaResource\RelationManagers;
use App\Models\SuperSolidaria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuperSolidariaResource extends Resource
{
    protected static ?string $model = SuperSolidaria::class;
    protected static ?string $cluster = InformesCumplimiento::class;
    protected static?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    protected static?string $navigationLabel = 'Informes  Super-Solidaria';
    protected static?string $modelLabel = 'Informes  Super-Solidaria';

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
            ->emptyStateHeading('Informes Super-Solidaria')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla todos los reportes a trasmitir a la SuperSolidaria.')

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
            'index' => Pages\ListSuperSolidarias::route('/'),
            'create' => Pages\CreateSuperSolidaria::route('/create'),
            'view' => Pages\ViewSuperSolidaria::route('/{record}'),
            'edit' => Pages\EditSuperSolidaria::route('/{record}/edit'),
        ];
    }
}
