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
    protected static?string $navigationIcon = 'heroicon-o-heart';
    protected static?string $navigationLabel = 'Super Solidarias';
    protected static?string $modelLabel = 'Super Solidarias';

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
            'index' => Pages\ListSuperSolidarias::route('/'),
            'create' => Pages\CreateSuperSolidaria::route('/create'),
            'view' => Pages\ViewSuperSolidaria::route('/{record}'),
            'edit' => Pages\EditSuperSolidaria::route('/{record}/edit'),
        ];
    }
}
