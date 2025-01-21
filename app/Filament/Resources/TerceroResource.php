<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TerceroResource\Pages;
use App\Filament\Resources\TerceroResource\RelationManagers;
use App\Models\Tercero;
use App\Models\Pais;
use App\Models\Ciudad;
use App\Models\Barrio;
use App\Models\NivelEscolar;
use App\Models\EstadoCivil;
use App\Models\Profesion;
use App\Models\Novedades;
use App\Models\TipoIdentificacion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Wizard;


class TerceroResource extends Resource
{
    protected static ?string $model = Tercero::class;

    protected static ?string    $navigationIcon = 'heroicon-o-user-group';
    protected static ?string    $navigationLabel = 'Creacion de Terceros';
    protected static ?string    $navigationGroup = 'Administracion de Terceros';
    protected static ?string    $recordTitleAttribute = 'tercero_id';
    protected static ?string    $modelLabel = 'Tercero';
    protected static ?string    $pluralModelLabel = 'Terceros';
    protected static ?string    $slug = 'Par/Tab/Terc';


    public static function form(Form $form): Form
    {
        return $form


            ->schema([
                Wizard::make()
                ->steps([
                Wizard\Step::make('Identificacion ')
                ->columns(4)
                ->schema([
                Radio::make('tipo_tercero')
                    ->required()
                    ->label('')
                    ->columnSpan(1)
                    ->live()
                    ->options([
                        'Natural' => 'Persona Natural',
                        'Juridica' => 'Persona Juridica',
                            ]),
                TextInput::make('tercero_id')
                    ->markAsRequired(false)
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(16)
                    ->columnSpan(2)
                    ->autocomplete(false)
                    ->prefix('Id')
                    ->rule('regex:/^[0-9]+$/')
                    ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                    ->label('No. de Identificacion'),
                TextInput::make('digito_verificacion')
                    ->maxLength(1)
                    ->markAsRequired(false)
                    ->columnSpan(1)
                    ->Hidden()
                    ->autocomplete(false)
                    ->label('Digito de Verificacion'),
                Select::make('tipo_identificacion_id')
                    ->options(function (Get $get) {
                        // Filtrar las opciones según el valor de tipo_tercero
                        $tipoTercero = $get('tipo_tercero');
                        if ($tipoTercero === 'Natural') {
                            return \App\Models\TipoIdentificacion::where('codigo', 'N') // Filtra por código 'N'
                                ->pluck('nombre', 'id'); // Obtiene nombre como texto y id como valor
                        } elseif ($tipoTercero === 'Juridica') {
                            return \App\Models\TipoIdentificacion::where('codigo', 'J') // Filtra por código 'J'
                                ->pluck('nombre', 'id');
                        }
                        return collect([]); // Retorna un conjunto vacío si no hay selección
                    })
                    ->columnSpan(1)
                    ->live()
                    ->required()
                    ->label('Tipo de Identificacion'),

                ])
                ->columnSpanFull(),
                Wizard\Step::make('Datos Basicos')
                ->columns(4)
                ->schema([
                    TextInput::make('nombres')
                    ->required()
                    ->markAsRequired(false)
                    ->autocomplete(false)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->maxLength(255)
                    ->columnSpan(2)
                    ->label('Nombres Completos'),
                TextInput::make('primer_apellido')
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(255)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->autocapitalize('words')
                    ->columnSpan(1)
                    ->label('Primer Apellido'),
                TextInput::make('segundo_apellido')
                    ->maxLength(255)
                    ->autocomplete(false)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->autocapitalize('words')
                    ->markAsRequired(false)
                    ->columnSpan(1)
                    ->label('Segundo Apellido'),
                TextInput::make('telefono')
                    ->markAsRequired(false)
                    ->required()
                    ->autocomplete(false)
                    ->columnSpan(1)
                    ->minLength(7)
                    ->rule('regex:/^[0-9]+$/')
                    ->maxLength(10)
                    ->label('No de Telefono'),
                TextInput::make('direccion')
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(255)
                    ->columnSpan(3)
                    ->label('Direccion Residencia'),
                Select::make('pais_id')
                        ->options(Pais::query()->pluck('nombre', 'id'))
                        ->markAsRequired(false)
                        ->required()
                        ->preload()
                        ->columnSpan(1)
                        ->live()
                        ->label('Pais de Residencia'),
                Select::make('ciudad_id')
                    ->options(fn (Get $get): Collection => Ciudad::query()
                    ->where('pais_id', $get('pais_id'))
                    ->pluck('nombre', 'id'))
                    ->markAsRequired(false)
                    ->required()
                    ->columnSpan(1)
                    ->live()
                    ->preload()
                    ->label('Ciudad de Residencia'),
                Select::make('barrio_id')
                    ->options(fn (Get $get): Collection => Barrio::query()
                    ->where('ciudad_id', $get('ciudad_id'))
                    ->pluck('nombre', 'id'))
                    ->markAsRequired(false)
                    ->required()
                    ->preload()
                    ->columnSpan(2)
                    ->live()
                    ->label('Barrio'),
                TextInput::make('celular')
                    ->required()
                    ->markAsRequired(false)
                    ->minLength(10)
                    ->columnSpan(1)
                    ->autocomplete(false)
                    ->rule('regex:/^[0-9]+$/')
                    ->suffixIcon('heroicon-m-phone')
                    ->maxLength(12)
                    ->label('Celular'),
                TextInput::make('email')
                    ->email()
                    ->markAsRequired(false)
                    ->required()
                    ->autocomplete(false)
                    ->maxLength(255)
                    ->suffixIcon('heroicon-m-envelope-open')
                    ->columnSpan(3)
                    ->label('Correo Electronico'),
                Textarea::make('observaciones')
                    ->maxLength(65535)
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->columnSpanFull(),
                Toggle::make('activo')
                    ->onIcon('heroicon-m-hand-thumb-up')
                    ->offColor('danger')
                    ->offIcon('heroicon-m-hand-thumb-down')
                    ->label('Autorización tratamiento de datos personales
                    FONDEP.
                    Responsable de los datos personales recolectados de sus Asociados con ocasión
                    de la prestación del servicio y en atención a la ley 1581 de 2012 y del Decreto 1377 de
                     2013, Autorizo para continuar con el tratamiento de mis datos que permita recaudar,
                     almacenar, usar, circular, suprimir, procesar, compilar, intercambiar, y en general la
                      información suministrada en este formulario, con fines que cumpla el objeto social de
                    FONDEP')
                    ->columnSpanFull()
                    ->required(),
/*                 CheckboxList::make('autorizacion')
                    ->label('Autorizo recibir información general de Fondep por el o los siguientes medios')
                    ->options([
                        'Correo_Electronico' => 'Correo_Electronico',
                        'SMS' => 'SMS',
                        'Whatsapp' => 'Whatsapp',
                        'Grupo_Whatsapp' => 'Grupo_Whatsapp',
                    ])
                ->columns(4)
                ->gridDirection('row')
                ->columnSpanFull(), */
                ]),
                ])->columnSpanFull(),

            ]);



    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tercero_id')
                    ->searchable(),
                TextColumn::make('nombres')
                    ->searchable(),
                TextColumn::make('primer_apellido')
                    ->searchable(),
                TextColumn::make('segundo_apellido')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->Hidden(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->Hidden(),

            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\TerceroSarlaftRelationManager::class,
            RelationManagers\InformacionFinancieraRelationManager::class,
            RelationManagers\ReferenciasRelationManager::class,
            RelationManagers\NovedadesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTerceros::route('/'),
            'create' => Pages\CreateTercero::route('/create'),
            'view' => Pages\ViewTercero::route('/{record}'),
            'edit' => Pages\EditTercero::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
