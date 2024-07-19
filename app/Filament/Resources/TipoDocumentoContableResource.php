<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TipoDocumentoContableResource\Pages;
use App\Filament\Resources\TipoDocumentoContableResource\RelationManagers;
use App\Models\TipoDocumentoContable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms;
use Filament\Forms\Form;
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


class TipoDocumentoContableResource extends Resource
{
    protected static ?string $model = TipoDocumentoContable::class;

    protected static ?string    $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string    $navigationLabel = 'Tipo Documento Contable';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Parametros Contabilidad';   
    protected static ?string    $modelLabel = 'Documento Tipo';

    public static function form(Form $form): Form
    {
        return $form
        ->columns(7)
            ->schema([
                TextInput::make('sigla')
                ->maxLength(3)
                ->columnSpan(1)
                ->unique(ignoreRecord: true)
                ->required()
                ->label('Sigla Documento'),
                TextInput::make('tipo_documento')
                ->maxLength(255)
                ->columnSpan(4)
                ->required()
                ->label('Documento'),
                TextInput::make('clase_origen')
                ->maxLength(255)
                ->columnSpan(2)
                ->required()
                ->label('Clase Documento-Origen'),
                TextInput::make('numerador')
                ->numeric() 
                ->required()
                ->maxLength(6)
                ->columnSpan(2),
                Toggle::make('fecha_modificable')
                ->required()
                ->label('Se permitido modificar la fecha al crearlo?')
                ->columnSpan(4), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sigla'),
                Tables\Columns\TextColumn::make('tipo_documento'),
                Tables\Columns\TextColumn::make('clase_origen'),
                Tables\Columns\TextColumn::make('numerador'),
            ])
            ->defaultSort('tipo_documento')
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
            'index' => Pages\ListTipoDocumentoContables::route('/'),
            'create' => Pages\CreateTipoDocumentoContable::route('/create'),
            'edit' => Pages\EditTipoDocumentoContable::route('/{record}/edit'),
        ];
    }    
}
