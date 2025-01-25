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
use App\Models\Moneda;
use App\Models\Pais;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\IconColumn;

class TerceroSarlaftRelationManager extends RelationManager
{
    protected static string $relationship = 'TerceroSarlaft';
    protected static ?string $modelLabel = 'Información Sarlaft';
    protected static ?string $pluralModelLabel = 'Información Sarlaft';
    protected static ?string $slug = 'Par/Tab/InfSarl';

    public function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Toggle::make('reconocimiento_publico')
                    ->label('¿Reconocimiento Público?')
                    ->required()
                    ->helperText('Indique si hay reconocimiento público')
                    ->columnSpan(2),

                TextInput::make('descripcion_reconocimiento')
                    ->label('Descripción del Reconocimiento')
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->placeholder('Ingrese la descripción')
                    ->maxLength(255)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->columnSpan(3),

                Toggle::make('ejerce_cargos_publicos')
                    ->label('¿Ejerce Cargos Públicos?')
                    ->required()
                    ->helperText('Indique si ejerce cargos públicos')
                    ->columnSpan(2),

                TextInput::make('descripcion_cargo_publico')
                    ->label('Descripción del Cargo Público')
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->placeholder('Ingrese la descripción del cargo')
                    ->maxLength(255)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->columnSpan(3),

                Toggle::make('familiar_peps')
                    ->label('¿Familiar de PEP?')
                    ->required()
                    ->helperText('Indique si es familiar de una Persona Expuesta Políticamente')
                    ->columnSpan(2),

                Select::make('parentesco_id')
                    ->label('Parentesco con PEP')
                    ->relationship('parentesco', 'nombre')
                    ->placeholder('Seleccione un parentesco')
                    ->columnSpan(2),

                TextInput::make('peps_id')
                    ->label('ID del PEP')
                    ->numeric()
                    ->helperText('Debe ser número entero')
                    ->columnSpan(1),

                Toggle::make('socio_peps')
                    ->label('¿Es socio de un PEP?')
                    ->required()
                    ->helperText('Indique si es socio de una Persona Expuesta Políticamente')
                    ->columnSpan(2),

                TextInput::make('nombre_peps')
                    ->label('Nombre del PEP')
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->placeholder('Ingrese el nombre completo del PEP')
                    ->maxLength(255)
                    ->rule('regex:/^[a-zA-Z\s-]+$/')
                    ->columnSpan(3),

                Toggle::make('operacion_moneda_extranjera')
                    ->label('¿Opera en Moneda Extranjera?')
                    ->required()
                    ->helperText('Indique si realiza operaciones en moneda extranjera')
                    ->columnSpan(2),

                Select::make('pais_id')
                    ->label('País de Operación')
                    ->relationship('pais', 'nombre')
                    ->placeholder('Seleccione un país')
                    ->columnSpan(2),

                Select::make('moneda_id')
                    ->label('Moneda de Operación')
                    ->relationship('moneda', 'nombre')
                    ->placeholder(null)
                    ->columnSpan(1),

                TextInput::make('producto_moneda_extranjera')
                    ->label('Producto en Moneda Extranjera')
                    ->placeholder('Ingrese el nombre del producto')
                    ->maxLength(255)
                    ->columnSpan(2),

                TextInput::make('tipo_producto_moneda_extranjera')
                    ->label('Tipo de Producto')
                    ->placeholder('Ingrese el tipo de producto')
                    ->maxLength(255)
                    ->columnSpan(2),

                TextInput::make('monto_inicial')
                    ->label('Monto Inicial')
                    ->numeric()
                    ->placeholder('Ingrese el monto inicial')
                    ->columnSpan(2),

                TextInput::make('monto_final')
                    ->label('Monto Final')
                    ->numeric()
                    ->placeholder('Ingrese el monto final')
                    ->columnSpan(2),

                Toggle::make('declara_renta')
                    ->label('¿Declara Renta?')
                    ->required()
                    ->columnSpan(1),

                Textarea::make('origen_fondos')
                    ->label(null)
                    ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
                        $set('ultimo_grado', ucwords(strtolower($state)));
                    })
                    ->placeholder('Describa el origen de los fondos')
                    ->maxLength(65535)
                    ->helperText('Sea lo más detallado posible')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->paginated(false)
        ->columns([
            IconColumn::make('reconocimiento_publico')
                ->label('¿Tiene reconocimiento Publico?')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('primary')
                ->alignment(Alignment::Center)
                ->size(IconColumn\IconColumnSize::Large)
                ->falseColor('danger'),
            IconColumn::make('ejerce_cargos_publicos')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('primary')
                ->alignment(Alignment::Center)
                ->size(IconColumn\IconColumnSize::Large)
                ->falseColor('danger')
                ->label('¿Ejerce Cargos Públicos?') ,
            IconColumn::make('familiar_peps')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('primary')
                ->alignment(Alignment::Center)
                ->size(IconColumn\IconColumnSize::Large)
                ->falseColor('danger')
                ->label('¿Familiar de PEP?'),
            IconColumn::make('operacion_moneda_extranjera')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('primary')
                ->alignment(Alignment::Center)
                ->size(IconColumn\IconColumnSize::Large)
                ->falseColor('danger')
                ->label('¿Opera en Moneda Extranjera?'),
            IconColumn::make('declara_renta')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('primary')
                ->alignment(Alignment::Center)
                ->size(IconColumn\IconColumnSize::Large)
                ->falseColor('danger')
                ->label('¿Declara Renta?'),
        ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->label('Actualizar Información'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label('Gestionar Información'),
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Agregar Información Sarlaft')
            ->emptyStateDescription('En este módulo podrás gestionar de forma sencilla la información Sarlaft.');
    }
}
