<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Tercero;
use Illuminate\Support\Collection;
use Filament\Resources\Resource;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;

class InformacionFinancieraRelationManager extends RelationManager
{
    protected static string $relationship = 'InformacionFinanciera';

    public function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                TextInput::make('total_activos')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('total_pasivos')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('total_patrimonio')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('salario')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('honorarios')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('otros_ingresos')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                TextInput::make('total_ingresos')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(9),
    
                TextInput::make('gastos_sostenimiento')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                
                TextInput::make('gastos_financieros')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                
                TextInput::make('creditos_hipotecarios')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(4),
    
                
                TextInput::make('otros_gastos')
                ->numeric() 
                ->required()
                ->markAsRequired(false)
                ->maxLength(10)
                ->columnSpan(9),                    
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            
            ->columns([
                Tables\Columns\TextColumn::make('tercero.tercero_id')
                ->label('Identificacion'),
                Tables\Columns\TextColumn::make('tercero.nombres')
                ->label('Nombres'),
                Tables\Columns\TextColumn::make('tercero.primer_apellido')
                ->label('Primer Apellido'),
                Tables\Columns\TextColumn::make('tercero.segundo_apellido')
                ->label('Segundo Apellido'),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Ultima Actualizacion'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->label('Actualizar Informacion'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
