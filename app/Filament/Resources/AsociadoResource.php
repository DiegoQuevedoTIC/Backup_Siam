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
    protected static ?int       $navigationSort = 2;
    protected static ?string    $pluralModelLabel = 'Asociados';
    protected static ?string    $slug = 'Par/Tab/Asoc';


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
                                ->prefix('Id')
                                ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                                ->label('No. de Identificacion'),

                        Radio::make('tipo_vinculo_id')
                                ->options([
                                    'N' => 'Descuento Nomina',
                                    'A' => 'Pago Abierto'
                                ]),
                        Select::make('pagaduria_id')
                                ->relationship('pagaduria', 'nombre')
                                ->required()
                                ->markAsRequired(false)
                                ->preload()
                                ->label('Pagaduria Asociado'),
                        TextInput::make('codigo_interno_pag')
                                ->markAsRequired(false)
                                ->maxLength(50)
                                ->label('Codigo Interno Asociado'),
                        Select::make('estado_cliente_id')
                                ->relationship('estadocliente', 'nombre')
                                ->required()
                                ->markAsRequired(false)
                                ->preload()
                                ->label('Estado del Cliente'),
                        Select::make('banco_id')
                                ->relationship('banco', 'nombre')
                                ->required()
                                ->columnSpan(1)
                                ->markAsRequired(false)
                                ->preload()
                                ->label('Banco principal del Cliente'),
                        TextInput::make('cuenta_cliente')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(7)
                                ->maxLength(20)
                                ->label('Cuenta de Deposito del Cliente'),
                        Textarea::make('observaciones_cliente')
                                ->maxLength(65535)
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
                                ->preload()
                                ->columnSpan(2)
                                ->label('Ciudad de Nacimiento'),
                        DatePicker::make('fecha_nacimiento')
                                ->markAsRequired(false)
                                ->required()
                                ->columnSpan(2)
                                ->label('Fecha de Nacimiento'),
                        Select::make('tipo_residencia_id')
                                ->options(TipoResidencia::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->preload()
                                ->columnSpan(2)
                                ->label('Tipo de vivienda'),
                        TextInput::make('tiempo_residencia')
                                ->markAsRequired(false)
                                ->required()
                                ->columnSpan(1)
                                ->minLength(1)
                                ->maxLength(2)
                                ->label('Tiempo residencia'),
                        Select::make('estado_civil_id')
                                ->options(EstadoCivil::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->preload()
                                ->columnSpan(2)
                                ->label('Estado Civil'),
                        TextInput::make('conyugue')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(7)
                                ->maxLength(255)
                                ->columnSpan(3)
                                ->label('Nombre del Familiar Principal'),
                        Select::make('parentesco_id')
                                ->options(Parentesco::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->preload()
                                ->columnSpan(2)
                                ->label('Parentesco'),
                        TextInput::make('direccion_conyugue')
                                ->required()
                                ->markAsRequired(false)
                                ->maxLength(255)
                                ->columnSpan(4)
                                ->label('Direccion Familiar Principal'),
                        TextInput::make('telefono_conyugue')
                                ->markAsRequired(false)
                                ->required()
                                ->columnSpan(2)
                                ->minLength(7)
                                ->maxLength(12)
                                ->label('Telefono Familiar Principal'),
                        Toggle::make('madre_cabeza')
                                ->required()
                                ->columnSpan(2)
                                ->label('Cabeza de Familia?'),
                        TextInput::make('no_hijos')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->columnSpan(2)
                                ->maxLength(2)
                                ->label('No de Hijos'),
                        TextInput::make('no_personas_cargo')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->columnSpan(2)
                                ->maxLength(2)
                                ->label('No de personas a cargo?'),
                        Select::make('genero_id')
                                ->required()
                                ->markAsRequired(false)
                                ->columnSpan(1)
                                ->placeholder('')
                                ->label('Genero')
                                ->options([
                                    'Masculino' => 'Masculino',
                                    'Femenino' => 'Femenino',
                                    'Otro' => 'Otro',
                                ]),

                            ]),
                    Wizard\Step::make('Datos Academicos ')
                    ->schema([
                        Select::make('nivel_escolar_id')
                                ->options(NivelEscolar::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
                                ->preload()
                                ->columnSpan(1)
                                ->label('Nivel Escolar'),
                        TextInput::make('ultimo_grado')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->maxLength(255)
                                ->label('Ultimo Grado Optenido'),
                        Select::make('profesion_id')
                                ->options(Profesion::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
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
                                ->preload()
                                ->columnSpan(1),
                        TextInput::make('empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->columnSpan(2)
                                ->maxLength(255)
                                ->label('Empresa Laboral'),
                        TextInput::make('telefono_empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->maxLength(12)
                                ->label('Telefono Empresa'),
                        TextInput::make('direccion_empresa')
                                ->markAsRequired(false)
                                ->required()
                                ->minLength(1)
                                ->columnSpan(2)
                                ->maxLength(255)
                                ->label('Direccion Empresa'),
                        DatePicker::make('fecha_ingreso_laboral')
                                ->markAsRequired(false)
                                ->required()
                                ->label('Fecha de Ingreso'),
                        Select::make('tipo_contrato_id')
                                ->options(TipoContrato::query()->pluck('nombre', 'id'))
                                ->markAsRequired(false)
                                ->required()
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
}
