<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoclaseResource\Pages;
use App\Filament\Resources\DocumentoclaseResource\RelationManagers;
use App\Models\Documentoclase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentoclaseResource extends Resource
{
    protected static ?string $model = Documentoclase::class;

    protected static ?string    $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string    $navigationLabel = 'Tablas de Retencion';
    protected static ?string    $navigationGroup = 'Gestion Documental';
    protected static ?string    $modelLabel = 'Tabla Retencion';
    protected static ?string    $pluralModelLabel = 'Tablas de Retencion';
    protected static ?string    $slug = 'Par/Tab/Retencion';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
            TextInput::make('sigla')
                ->label('Sigla')
                ->required()
                ->autocomplete(false)
                ->columnSpan(1)
                ->maxLength(3),
            TextInput::make('nombre')
                ->label('Nombre de la Clase de Documento')
                ->required()
                ->autocomplete(false)
                ->columnSpan(5)
                ->maxLength(255),
            Textarea::make('descripcion')
                ->label('Descripción')
                ->autocomplete(false)
                ->rows(3)
                ->columnSpan(6)
                ->maxLength(500)
                ->hint('Opcional: Breve descripción de la clase de documento.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sigla')
                        ->label('Clase'),
                TextColumn::make('nombre')
                        ->label('Nombre del Documento'),
                TextColumn::make('descripcion')
                        ->label('Descripcion'),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\DocumentotiposRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentoclases::route('/'),
            'create' => Pages\CreateDocumentoclase::route('/create'),
            'edit' => Pages\EditDocumentoclase::route('/{record}/edit'),
        ];
    }
}
