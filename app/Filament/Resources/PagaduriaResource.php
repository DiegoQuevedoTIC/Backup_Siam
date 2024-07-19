<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagaduriaResource\Pages;
use App\Filament\Resources\PagaduriaResource\RelationManagers;
use App\Models\Pagaduria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagaduriaResource extends Resource
{
    protected static ?string $model = Pagaduria::class;

    protected static ?string    $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string    $navigationLabel = 'Pagadurias';
    protected static ?string    $navigationGroup = 'Parametros';
    protected static ?string    $navigationParentItem = 'Parametros Asociados';
    protected static ?string    $modelLabel = 'Pagaduria';
    protected static ?string    $pluralModelLabel = 'Pagadurias';
    protected static ?string    $slug = 'Par/Tab/Pagad';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('codigo')
                ->required()
                ->maxLength(3),
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('codigo')
                ->searchable(),
            Tables\Columns\TextColumn::make('nombre')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePagadurias::route('/'),
        ];
    }
}
