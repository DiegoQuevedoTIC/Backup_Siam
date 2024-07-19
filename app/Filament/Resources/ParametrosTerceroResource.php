<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ParametrosTerceroResource\Pages;
use App\Filament\Resources\ParametrosTerceroResource\RelationManagers;
use App\Models\ParametrosTercero;
use App\Models\Parentesco;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;

class ParametrosTerceroResource extends Resource
{
    protected static ?string $model = ParametrosTercero::class;

    protected static ?string    $navigationIcon = 'heroicon-o-bars-arrow-up';
    protected static ?string    $navigationLabel = 'Parametros Terceros';
    protected static ?string    $navigationGroup = 'Parametros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
{
    return $page->generateNavigationItems([
        Pages\EditParametrosTercero::class,
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
            'index' => Pages\ListParametrosTerceros::route('/'),
            'create' => Pages\CreateParametrosTercero::route('/create'),
            'edit' => Pages\EditParametrosTercero::route('/{record}/edit'),
        ];
    }
}
