<?php

namespace App\Filament\Resources\DocumentoclaseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Documentoclase;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentotiposRelationManager extends RelationManager
{
    protected static string     $relationship = 'documentotipos';
    protected static ?string    $modelLabel = 'Tipo de Documentos';
    protected static ?string    $pluralModelLabel = 'Documentos Tipos';
    protected static ?string    $navigationLabel = 'Tipo de Documento';
    protected static ?string    $slug = 'Par/Tab/TipoDoc';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('nombre')
                ->label('Nombre del Tipo de Documento')
                ->required()
                ->autocomplete(false)
                ->maxLength(255),
            Textarea::make('descripcion')
                ->label('Descripción')
                ->rows(2)
                ->autocomplete(false)
                ->maxLength(500)
                ->hint('Opcional: Breve descripción del tipo de documento.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                TextColumn::make('nombre')
                ->label('Tipo de Documento'),
                TextColumn::make('descripcion')
                ->label('Descripción'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('+ Nuevo Tipo de Documento'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
