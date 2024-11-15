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
    protected static ?int $navigationSort = 2;

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
            'index' => Pages\ListInformacionExogenas::route('/'),
            'create' => Pages\CreateInformacionExogena::route('/create'),
            'view' => Pages\ViewInformacionExogena::route('/{record}'),
            'edit' => Pages\EditInformacionExogena::route('/{record}/edit'),
        ];
    }
}
