<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AsociadoResource\Pages;
use App\Filament\Resources\AsociadoResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Wizard;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RelationSearchInput;
use Filament\Forms\ComponentGroups\InputGroup;
use Filament\Forms\Get;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;
use App\Models\Asociado;
use App\Models\Tercero;
use App\Models\Pagaduria;
use App\Models\EstadoCliente;
use App\Models\Banco;
use App\Models\Ciudad;
use App\Models\TipoResidencia;
use App\Models\EstadoCivil;
use App\Models\Profesion;
use App\Models\Parentesco;
use App\Models\NivelEscolar;
use App\Models\ActividadEconomica;
use App\Models\TipoContrato;

class AsociadoResource extends Resource
{
    protected static ?string $model = Asociado::class;


    protected static ?string    $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string    $navigationLabel = 'Vincular Asociado';
    protected static ?string    $navigationGroup = 'Administracion de Terceros';
    protected static ?string    $modelLabel = 'Asociado';
    protected static ?string    $pluralModelLabel = 'Asociados';
    protected static ?string    $slug = 'Par/Tab/Asoc';
    protected static ?int       $navigationSort = 2;

public static function form(Form $form): Form
{
        return $form
        ->schema([
            Wizard::make()
                ->steps([
                    Wizard\Step::make('Datos Asociados ')
                    ->columns(4)
                    ->schema([
                        Select::make('tercero_id')
                                ->relationship('tercero', 'tercero_id')
                                ->markAsRequired(false)
                                ->required()
                                ->searchable()
                                ->unique(ignoreRecord: true)
                                ->columnSpan(2)
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'unique'=> 'El asociado ya esta registrado, si deseas modificarlo ve a la seccion de edicion'
                                ])
                                ->live(onBlur: true)
                                ->prefix('Id')
                                ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                                ->label('No. de Identificacion')
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        $tercero = \App\Models\Tercero::find($state);
                                        $set('codigo_interno_pag', $tercero?->tercero_id);
                                    }
                                }),
                        Radio::make('tipo_vinculo_id')
                                ->required()
                                ->markAsRequired(false)
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->options([
                                    'N' => 'Descuento Nomina',
                                    'A' => 'Pago Abierto'
                                ]),
                        Select::make('pagaduria_id')
                                ->relationship('pagaduria', 'nombre')
                                ->required()
                                ->markAsRequired(false)
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->markAsRequired(false)
                                ->label('Pagaduria Asociado'),
                        TextInput::make('codigo_interno_pag')
                                ->markAsRequired(false)
                                ->maxLength(50)
                                ->readonly()
                                ->autocomplete(false)
                                ->label('Codigo Interno Asociado'),
                        Select::make('estado_cliente_id')
                                ->relationship('estadocliente', 'nombre')
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->markAsRequired(false)
                                ->preload()
                                ->label('Estado del Cliente'),
                        Select::make('banco_id')
                                ->relationship('banco', 'nombre')
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->columnSpan(1)
                                ->markAsRequired(false)
                                ->preload()
                                ->label('Banco principal del Cliente'),
                        TextInput::make('cuenta_cliente')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'La cuenta debe tener al menos 7 caracteres',
                                    'max'=> 'La cuenta no puede tener mas de 20 caracteres',
                                    'regex'=> 'Solo se permiten numeros'
                                ])
                                ->minLength(7)
                                ->autocomplete(false)
                                ->maxLength(20)
                                ->rule('regex:/^[0-9]+$/')
                                ->label('Cuenta de Deposito del Cliente'),
                        Textarea::make('observaciones_cliente')
                                ->label('Observaciones')
                                ->maxLength(65535)
                                ->autocomplete(false)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                    $set('observaciones_cliente', ucwords(strtolower($state)));
                                })
                                ->placeholder('Puedes aca colocar caracteristicas particulares sobre el asociado')
                                ->markAsRequired(false)
                                ->columnSpanFull(),
                            ]),
                    Wizard\Step::make('Datos Personales ')
                    ->columns(7)
                    ->schema([
                        Select::make('ciudad_nacimiento_id')
                                ->options(Ciudad::query()->orderBy('nombre')->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->preload()
                                ->columnSpan(2)
                                ->label('Ciudad de Nacimiento'),
                        DatePicker::make('fecha_nacimiento')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->columnSpan(2)
                                ->label('Fecha de Nacimiento'),
                        Select::make('tipo_residencia_id')
                                ->options(TipoResidencia::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->preload()
                                ->columnSpan(2)
                                ->label('Tipo de vivienda'),
                        TextInput::make('tiempo_residencia')
                                ->markAsRequired(false)
                                ->required()
                                ->suffix('Años')
                                ->rule('regex:/^[0-9]+$/')
                                ->validationMessages([
                                    'required'=> 'Campo Requerido',
                                    'min'=> 'El tiempo de residencia debe tener al menos 1 caracter',
                                    'max'=> 'El tiempo de residencia no puede tener mas de 2 caracteres',
                                    'regex'=> 'Solo se permiten numeros'
                                ])
                                ->columnSpan(1)
                                ->autocomplete(false)
                                ->minLength(1)
                                ->maxLength(2)
                                ->label('Tiempo Residencia'),
                        Select::make('estado_civil_id')
                                ->options(EstadoCivil::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->preload()
                                ->columnSpan(2)
                                ->label('Estado Civil'),
                        Select::make('genero_id')
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->markAsRequired(false)
                                ->columnSpan(1)
                                ->placeholder('')
                                ->label('Genero')
                                ->options([
                                    'Masculino' => 'Masculino',
                                    'Femenino' => 'Femenino',
                                    'Otro' => 'Otro',
                                ]),
                        TextInput::make('no_personas_cargo')
                                ->markAsRequired(false)
                                ->required()
                                ->suffix('N°')
                                ->validationMessages([
                                    'required'=> 'Campo Requerido',
                                    'min'=> 'El numero de personas a cargo debe tener al menos 1 caracter',
                                    'max'=> 'El numero de personas a cargo no puede tener mas de 2 caracteres',
                                    'regex'=> 'Solo se permiten numeros',
                                ])
                                ->minLength(1)
                                ->autocomplete(false)
                                ->columnSpan(1)
                                ->rule('regex:/^[0-9]+$/')
                                ->maxLength(2)
                                ->label('Personas a Cargo'),
                        TextInput::make('no_hijos')
                                ->markAsRequired(false)
                                ->required()
                                ->rule('regex:/^[0-9]+$/')
                                ->validationMessages([
                                    'required'=> 'Campo Requerido',
                                    'min'=> 'El numero de personas a cargo debe tener al menos 1 caracter',
                                    'max'=> 'El numero de personas a cargo no puede tener mas de 2 caracteres',
                                    'regex'=> 'Solo se permiten numeros',
                                ])
                                ->minLength(1)
                                ->suffix('N°')
                                ->autocomplete(false)
                                ->columnSpan(1)
                                ->maxLength(2)
                                ->label('Cantidad Hijos'),

                        Toggle::make('madre_cabeza')
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Campo Requerido'
                                ])
                                ->columnSpan(2)

                                ->label('Cabeza de Familia?'),

                    Section::make('Información del Familiar de Contacto')
                                ->description('Proporcione los datos de contacto del familiar principal')
                                ->columns(7)
                                ->icon('heroicon-m-user-group')
                                ->schema([
                        TextInput::make('conyugue')
                                ->markAsRequired(false)
                                ->required()
                                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                    $set('conyugue', ucwords(strtolower($state)));
                                })
                                ->rule('regex:/^[a-zA-Z\s-]+$/')
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'El nombre del conyugue debe tener al menos 7 caracteres',
                                    'max'=> 'El nombre del conyugue no puede tener mas de 255 caracteres',
                                    'regex'=> 'Solo se permiten letras'

                                ])
                                ->minLength(7)
                                ->autocomplete(false)
                                ->maxLength(255)
                                ->columnSpan(3)
                                ->label('Nombre del Familiar Principal'),
                        Select::make('parentesco_id')
                                ->options(Parentesco::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->preload()
                                ->columnSpan(2)
                                ->label('Parentesco'),
                        TextInput::make('telefono_conyugue')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'min' => 'El numero es demasiado corto',
                                    'regex' => 'Solo se permiten numeros',
                                    'max' => 'El numero es demasiado largo',
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->rule('regex:/^[0-9]+$/')
                                ->autocomplete(false)
                                ->columnSpan(2)
                                ->minLength(7)
                                ->maxLength(12)
                                ->label('Telefono Familiar Principal'),
                        TextInput::make('direccion_conyugue')
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'La direccion del conyugue debe tener al menos 7 caracteres',
                                    'max'=> 'La direccion del conyugue no puede tener mas de 255 caracteres',
                                ])
                                ->markAsRequired(false)
                                ->minLength(7)
                                ->maxLength(255)
                                ->autocomplete(false)
                                ->columnSpan(4)
                                ->label('Direccion Familiar Principal'),

                                ])

                            ]),
                    Wizard\Step::make('Datos Academicos ')
                    ->columns(3)
                    ->schema([
                        Select::make('nivel_escolar_id')
                                ->options(NivelEscolar::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->preload()
                                ->columnSpan(1)
                                ->label('Nivel Escolar'),
                        TextInput::make('ultimo_grado')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'El grado debe tener al menos 2 caracteres',
                                    'max'=> 'El grado no puede tener mas de 255 caracteres',
                                ])
                                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                    $set('ultimo_grado', ucwords(strtolower($state)));
                                })
                                ->minLength(2)
                                ->autocomplete(false)
                                ->maxLength(255)
                                ->label('Ultimo Grado Optenido'),
                        Select::make('profesion_id')
                                ->options(Profesion::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->preload()
                                ->columnSpan(1)
                                ->label('Profesion'),
                            ]),
                    Wizard\Step::make('Datos Laborales ')
                    ->columns(4)
                    ->schema([
                        Select::make('actividad_economica_id')
                                ->options(ActividadEconomica::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                                    $set('ultimo_grado', ucwords(strtolower($state)));
                                })
                                ->preload()
                                ->columnSpan(1),
                        TextInput::make('empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'La empresa debe tener al menos 1 caracter',
                                    'max'=> 'La empresa no puede tener mas de 255 caracteres',
                                ])
                                ->minLength(1)
                                ->autocomplete(false)
                                ->columnSpan(2)
                                ->maxLength(255)
                                ->label('Empresa Laboral'),
                        TextInput::make('telefono_empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'El telefono de la empresa debe tener al menos 7 caracteres',
                                    'max'=> 'El telefono de la empresa no puede tener mas de 12 caracteres',
                                    'regex'=> 'Solo se permiten numeros'
                                ])
                                ->minLength(7)
                                ->rule('regex:/^[0-9]+$/')
                                ->autocomplete(false)
                                ->maxLength(12)
                                ->label('Telefono Empresa'),
                        TextInput::make('direccion_empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido',
                                    'min'=> 'La direccion de la empresa debe tener al menos 1 caracter',
                                    'max'=> 'La direccion de la empresa no puede tener mas de 255 caracteres',
                                ])
                                ->minLength(7)
                                ->autocomplete(false)
                                ->columnSpan(2)
                                ->maxLength(255)
                                ->label('Direccion Empresa'),
                        DatePicker::make('fecha_ingreso_laboral')
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->label('Fecha de Ingreso'),
                        Select::make('tipo_contrato_id')
                                ->options(TipoContrato::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->validationMessages([
                                    'required'=> 'Este campo es requerido'
                                ])
                                ->preload()
                                ->columnSpan(1)
                                ->label('Tipo de Contrato'),

                            ]),

                            ])->columnSpanFull(),

                        ]);
}




        public static function table(Table $table): Table
    {
        return $table
            ->heading('Asociados')
            ->description('Vinculacion de terceros como asociados Fondep. Es necesario ser asocido para poder acceder a los servicios de Creditos, Aportes, ahorros y CDAT.')
            ->striped()
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('tercero.tercero_id')
                ->label('Identificacion')
                ->searchable(),
                Tables\Columns\TextColumn::make('tercero.nombres')
                ->label('Nombres'),
                Tables\Columns\TextColumn::make('tercero.primer_apellido')
                ->label('Primer Apellido'),
                Tables\Columns\TextColumn::make('tercero.segundo_apellido')
                ->label('Segundo Apellido'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha Vinculacion'),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Ultima Actualizacion'),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsociados::route('/'),
            'create' => Pages\CreateAsociado::route('/create'),
            'edit' => Pages\EditAsociado::route('/{record}/edit'),

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
