<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosGenerales;
use App\Filament\Resources\CiudadResource\Pages;
use App\Models\Ciudad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CiudadResource\RelationManagers;



class CiudadResource extends Resource
{
        protected static ?string $model = Ciudad::class;
        protected static ?string    $cluster = ParametrosGenerales::class;
        protected static ?string    $navigationIcon = 'heroicon-o-globe-alt';
        protected static ?string    $navigationLabel = 'Ubicacion Geografica';
        protected static ?string    $navigationGroup = 'Parametros';
        protected static ?string    $navigationParentItem = 'Parametros Terceros';
        protected static ?string    $modelLabel = 'Ciudad';
        protected static ?string    $pluralModelLabel = 'Ciudades';
        protected static ?string    $slug = 'Par/Tab/Ciu';


    public static function form(Form $form): Form
    {
        return $form
                ->schema([
                    Forms\Components\TextInput::make('nombre')
                        ->required()
                        ->autocomplete(false)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('codigo_dane')
                        ->required()
                        ->autocomplete(false)
                        ->maxLength(10),
                    Forms\Components\Select::make('pais_id')
                        ->relationship('pais', 'nombre')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('codigo')
                                ->required()
                                ->autocomplete(false)
                                ->maxLength(3),
                            Forms\Components\TextInput::make('nombre')
                                ->required()
                                ->autocomplete(false)
                                ->maxLength(255),
                            Forms\Components\Select::make('lenguaje')
                                ->options([
                                    'Español' => 'Español',
                                    'Ingles' => 'Ingles',
                                    'Frances' => 'Frances',
                                ])
                                ->required(),
                            Forms\Components\Select::make('moneda')
                                ->options([
                                    'Peso' => 'Peso',
                                    'Dolar' => 'Dolar',
                                    'Euro' => 'Euro',
                                ])
                        ])
                        ->required(),

                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('codigo_dane')
                ->sortable(),
                Tables\Columns\TextColumn::make('pais.nombre')
                ->sortable(),
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


    public static function getRelations(): array
        {
            return [
            RelationManagers\BarriosRelationManager::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCiudads::route('/'),
            'create' => Pages\CreateCiudad::route('/create'),
            'edit' => Pages\EditCiudad::route('/{record}/edit'),
        ];
    }

}
