<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TerceroResource\Pages;
use App\Filament\Resources\TerceroResource\RelationManagers;
use App\Models\Tercero;
use App\Models\Pais;
use App\Models\Ciudad;
use App\Models\Barrio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class TerceroResource extends Resource
{
    protected static ?string $model = Tercero::class;

    protected static ?string    $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string    $navigationLabel = 'Creacion de Tercero';
    protected static ?string    $navigationGroup = 'Administracion de Terceros';
    protected static ?string    $recordTitleAttribute = 'tercero_id';
    protected static ?string    $modelLabel = 'Tercero';
    protected static ?string    $pluralModelLabel = 'Terceros';
    protected static ?string    $slug = 'Par/Tab/Terc';
    protected static ?int       $navigationSort = 1;



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
                    ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                    ->validationMessages([
                        'required'=> 'Este campo es requerido'
                    ])
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
                    ->validationMessages([
                        'regex' => 'Valide el numero ID,  Solo se permiten numeros',
                        'max' => 'Valide el numero ID,  El numero es demasiado largo',
                        'unique' => 'Valide el numero ID,  Ya existe el registro',
                        'required'=> 'Valide el numero ID,  Este campo es requerido'
                    ])

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
                    ->validationMessages([
                        'required'=> 'Este campo es requerido'
                    ])
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
                    ->validationMessages([
                        'max' => 'El numero es demasiado largo',
                        'required'=> 'Este campo es requerido'
                    ])
                    ->markAsRequired(false)
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->autocomplete(false)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->columnSpan(2)
                    ->label('Nombres Completos'),
                TextInput::make('primer_apellido')
                    ->required()
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->autocapitalize('words')
                    ->columnSpan(1)
                    ->label('Primer Apellido'),
                TextInput::make('segundo_apellido')
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->autocomplete(false)
                    ->live(onBlur: true)
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
                    ->validationMessages([
                        'min' => 'El numero es demasiado corto',
                        'regex' => 'Solo se permiten numeros',
                        'max' => 'El numero es demasiado largo',
                        'required'=> 'Este campo es requerido'
                    ])
                    ->rule('regex:/^[0-9]+$/')
                    ->maxLength(10)
                    ->label('No de Telefono'),
                TextInput::make('direccion')
                    ->required()
                    ->validationMessages([
                        'max' => 'El numero es demasiado largo',
                        'required'=> 'Este campo es requerido'
                    ])
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
                    ->validationMessages([
                        'min' => 'El numero es demasiado corto',
                        'max' => 'El numero es demasiado largo',
                        'required'=> 'Este campo es requerido',
                        'regex' => 'Solo se permiten numeros',
                    ])
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
                    ->validationMessages([
                        'max' => 'El numero es demasiado largo',
                        'required'=> 'Este campo es requerido',
                    ])
                    ->autocomplete(false)
                    ->maxLength(255)
                    ->suffixIcon('heroicon-m-envelope-open')
                    ->columnSpan(3)
                    ->label('Correo Electronico'),
                Textarea::make('observaciones')
                    ->maxLength(65535)
                    ->autocomplete(false)
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
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
                FileUpload::make('ruta_imagen')
                    ->label('Autorizacion de Manejo de Datos')
                    ->getUploadedFileNameForStorageUsing(
                        fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('Autorizacion-AU'),
                    )
                    ->columnSpan(6)
                    ->openable()
                    ->deletable(false)
                    ->downloadable()
                    ->previewable(true)
                    ->disk('spaces')
                    ->directory('autorizaciones')
                    ->visibility('public'),
                ]),
                ])->columnSpanFull(),

            ]);



    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Terceros')
            ->description('Administracion de Terceros Naturales y Juridicos.')
            ->striped()
            ->defaultPaginationPageOption(5)
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('tercero_id')
                    ->searchable()
                    ->label('No. de Identificacion'),
                TextColumn::make('nombres')
                    ->searchable()
                    ->label('Nombres'),
                TextColumn::make('primer_apellido')
                    ->searchable()
                    ->label('Primer Apellido'),
                TextColumn::make('segundo_apellido')
                    ->searchable()
                    ->label('Segundo Apellido'),
                TextColumn::make('celular')
                    ->searchable()
                    ->label('No Celular'),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Fecha de Creacion')
                    ->hidden(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->label('Última Actualización'),

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
            RelationManagers\PatrimonioRelationManager::class,
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
