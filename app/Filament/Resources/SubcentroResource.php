<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubcentroResource\Pages;
use App\Filament\Resources\SubcentroResource\RelationManagers;
use App\Models\Subcentro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions\Action;

class SubcentroResource extends Resource
{
    protected static ?string $model = Subcentro::class;

    protected static ?string    $navigationIcon = 'heroicon-o-presentation-chart-line'; 
    protected static ?string    $navigationLabel = 'Subcentros de Costos ';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Parametros Contabilidad';   
    protected static ?string    $modelLabel = 'Subcentro';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(9)
            ->schema([
                TextInput::make('subcentro')
                ->maxLength(10)
                ->columnSpan(3)
                ->required()
                ->label('Codigo Subcentro'),
                TextInput::make('descripcion')
                ->maxLength(255)
                ->columnSpan(6)
                ->required()
                ->label('Nombre del Subcentro'),
                Toggle::make('movimiento')
                ->required()
                ->label('Es subcentro que genera movimiento?')
                ->columnSpan(9), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subcentro'),
                Tables\Columns\TextColumn::make('descripcion'),
            ])
            ->defaultSort('subcentro')
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListSubcentros::route('/'),
            'create' => Pages\CreateSubcentro::route('/create'),
            'edit' => Pages\EditSubcentro::route('/{record}/edit'),
        ];
    }    
}
