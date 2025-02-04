<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Toggle;

class PatrimonioRelationManager extends RelationManager
{
    protected static string $relationship = 'Patrimonio';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('tipo')
                    ->label('Tipo de Patrimonio')
                    ->columnSpanFull()
                    ->options([
                        'bienes_inmuebles' => 'Bienes Inmuebles',
                        'vehiculos' => 'Vehículos',
                        'otros_bienes' => 'Otros Bienes',
                    ])
                    ->reactive()
                    ->required(),
                Fieldset::make()
                    ->columns(8)
                    ->schema([
                        Select::make('tipo_inmueble')
                            ->label('Tipo de Inmueble')
                            ->columnSpan(3)
                            ->options([
                                'Casa' => 'Casa',
                                'Apartamento' => 'Apartamento',
                                'Lote' => 'Lote',
                                'Finca' => 'Finca',
                            ]),
                        TextInput::make('direccion')
                            ->label('Dirección')
                            ->columnSpan(5),
                        TextInput::make('valor_comercial_inmueble')
                            ->numeric()
                            ->columnSpan(3)
                            ->prefix('$')
                            ->label('Valor Comercial'),
                        TextInput::make('hipoteca_favor')
                            ->columnSpan(2)
                            ->label('Hipoteca a favor de'),
                        TextInput::make('valor_pendiente_pago_inmueble')
                            ->numeric()
                            ->columnSpan(3)
                            ->prefix('$')
                            ->label('Valor pendiente de pago'),
                    ])
                    ->label('Bienes Inmuebles')
                    ->hidden(fn($get) => $get('tipo') !== 'bienes_inmuebles'),

                Fieldset::make()
                    ->columns(7)
                    ->schema([
                        Select::make('vehiculo_clase')
                            ->options([
                                'Motocicleta' => 'Motocicleta',
                                'Automovil' => 'Automovil',
                                'Campero' => 'Campero',
                                'Camioneta' => 'Camioneta',
                                'Bus' => 'Bus',
                            ])
                            ->columnSpan(3)
                            ->label('Clase de Vehículo'),
                        TextInput::make('valor_comercial_vehiculo')
                            ->numeric()
                            ->columnSpan(4)
                            ->prefix('$')
                            ->label('Valor Comercial'),
                        TextInput::make('marca_referencia')
                            ->label('Marca/Referencia')
                            ->columnSpan(3),
                        TextInput::make('numero_placa')
                            ->label('Número de Placa')
                            ->columnSpan(2),
                        TextInput::make('modelo')
                            ->numeric()
                            ->columnSpan(2)
                            ->label('Modelo'),
                        TextInput::make('valor_pendiente_pago_vehiculo')
                            ->numeric()
                            ->prefix('$')
                            ->label('Valor pendiente de pago')
                            ->columnSpan(4),
                        TextInput::make('reserva_dominio')
                            ->columnSpan(2)
                            ->label('Reserva de Dominio con'),
                    ])
                    ->label('Vehiculos')
                    ->hidden(fn($get) => $get('tipo') !== 'vehiculos'),

                Fieldset::make()->schema([
                    Textarea::make('descripcion_otros')
                        ->label('Descripción'),
                    TextInput::make('valor_comercial_otros')
                        ->numeric()
                        ->prefix('$')
                        ->label('Valor Comercial'),
                    Toggle::make('pignorado')->label('¿Pignorado?'),
                ])
                    ->label('Otros Bienes')
                    ->hidden(fn($get) => $get('tipo') !== 'otros_bienes'),
            ]);
    }




    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de Bien'),
                Tables\Columns\TextColumn::make('valor_comercial_inmueble')
                    ->formatStateUsing(fn($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
                    ->label('Valor Inmueble'),
                Tables\Columns\TextColumn::make('valor_comercial_vehiculo')
                    ->formatStateUsing(fn($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
                    ->label('Valor Vehiculo'),
                Tables\Columns\TextColumn::make('valor_comercial_otros')
                    ->formatStateUsing(fn($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
                    ->label('Valor Otros'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('+ Agregar Nueva Bien Patrimonial'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->label('Actualizar Bien'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make('create')
                    ->label('Gestionar Información'),
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Agregar Patrimonio')
            ->emptyStateDescription('En este módulo podrás gestionar de forma sencilla la información patrimonial.');
    }
}
