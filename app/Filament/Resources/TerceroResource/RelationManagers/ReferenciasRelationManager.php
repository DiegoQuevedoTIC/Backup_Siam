<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\Tercero;
use App\Models\Parentesco;
use Illuminate\Support\Collection;
use Filament\Resources\Resource;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;



class ReferenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'Referencias';

    public function form(Form $form): Form
    {
        return $form
            ->columns(6)
            ->schema([
                Radio::make('tipo_referencia')
                    ->options([
                        'Personal' => 'Referencia Personal',
                        'Familiar' => 'Referencia Familiar',
                        'Comercial' => 'Referencia Comercial'
                    ])
                ->columnSpan(2),
                TextInput::make('nombre_referencia')
                ->required()
                ->autocomplete(false)
                ->rule('regex:/^[a-zA-Z\s-]+$/')
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpan(4),
                Select::make('parentesco_id')
                ->relationship('parentesco', 'nombre')
                ->columnSpan(1),
                TextInput::make('direccion_referencia')
                ->required()
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpan(3),
                TextInput::make('telefono_referencia')
                ->required()
                ->rule('regex:/^[0-9]+$/')
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpan(2),
                TextInput::make('mail_referencia')
                ->required()
                ->email()
                ->autocomplete(false)
                ->markAsRequired(false)
                ->suffixIcon('heroicon-m-envelope-open')
                ->columnSpan(4),
                TextInput::make('direccion_oficina_referencia')
                ->required()
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpan(3),
                TextInput::make('telefono_oficina_referencia')
                ->required()
                ->rule('regex:/^[0-9]+$/')
                ->autocomplete(false)
                ->markAsRequired(false)
                ->columnSpan(2),
                TextInput::make('mail_oficina_referencia')
                ->required()
                ->autocomplete(false)
                ->email()
                ->suffixIcon('heroicon-m-envelope-open')
                ->markAsRequired(false)
                ->columnSpan(4),
                Textarea::make('observaciones')
                ->maxLength(65535)
                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                    $set('ultimo_grado', ucwords(strtolower($state)));
                })
                ->markAsRequired(false)
                ->autocomplete(false)
                ->columnSpanFull(),
                ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_referencia')
                ->label('Tipo de Referencia'),
                Tables\Columns\TextColumn::make('nombre_referencia')
                ->label('Refencia'),
                Tables\Columns\TextColumn::make('parentesco.nombre')
                ->label('Parentesco'),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Ultima Actualizacion'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('+ Agregar Nueva Referencia'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('warning')
                ->label('Actualizar Referencia'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])
                ->emptyStateActions([
                    Tables\Actions\CreateAction::make('create')
                    ->label('Gestionar Información'),
                ])
                ->emptyStateIcon('heroicon-o-bookmark')
                ->emptyStateHeading('Agregar Referencias')
                ->emptyStateDescription('En este módulo podrás gestionar de forma sencilla la Referencias.');
    }
}
