<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosContabilidad;
use App\Filament\Resources\PucResource\Pages;
use App\Filament\Resources\PucResource\RelationManagers;
use App\Models\Puc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Validation\ValidationException;

class PucResource extends Resource
{
    protected static ?string $model = Puc::class;

    protected static ?string    $cluster = ParametrosContabilidad::class;
    protected static ?string    $navigationIcon = 'heroicon-o-swatch';
    protected static ?string    $navigationLabel = 'Plan Unico de Cuentas';
    protected static ?string    $navigationParentItem = 'Parametros Contabilidad';
    protected static ?string    $modelLabel = 'PUC - Cuenta';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
                TextInput::make('puc')
                    ->maxLength(10)
                    ->columnSpan(2)
                    ->required()
                    ->autocomplete(false)
                    ->label('Cuenta PUC')
                    ->rule('regex:/^[0-9]+$/')
                    ->afterStateUpdated(function (callable $set, $state) {
                        $firstDigit = !empty($state) ? substr($state, 0, 1) : '';
                        $set('grupo', $firstDigit);

                        $length = strlen($state);
                        if ($length == 1) {
                            $set('nivel', 1);
                            $set('puc_padre', null); // Grupo 1, no tiene puc_padre
                        } elseif ($length == 2) {
                            $set('nivel', 2);
                            $set('puc_padre', $firstDigit); // Grupo 2, el puc_padre es el primer dígito
                        } else {
                            // Para grupos 3 en adelante, calcular el puc_padre
                            $pucPadre = substr($state, 0, $length - 2);
                            $set('nivel', $length == 3 || $length == 4 ? 3 : ($length == 5 || $length == 6 ? 4 : ($length == 7 || $length == 8 ? 5 : 6)));
                            $set('puc_padre', $pucPadre);

                            // Validar si el puc_padre existe en la base de datos
                            if (!\App\Models\Puc::where('puc', $pucPadre)->exists()) {
                                throw ValidationException::withMessages([
                                    'puc_padre' => 'El PUC padre no existe en la base de datos.',
                                ]);
                            }
                        }
                    })
                    ->live(onBlur: true),
                TextInput::make('grupo')
                    ->maxLength(1)
                    ->columnSpan(1)
                    ->required()
                    ->autocomplete(false)
                    ->label('Grupo Cuenta')
                    ->readOnly()
                    ->live(),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->columnSpan(4)
                    ->required()
                    ->autocomplete(false)
                    ->label('Descripcion Cuenta'),
                TextInput::make('nivel')
                    ->maxLength(6)
                    ->live()
                    ->autocomplete(false)
                    ->columnSpan(1)
                    ->required()
                    ->readOnly()
                    ->label('Nivel Cuenta'),
                TextInput::make('puc_padre')
                    ->label('Puc Padre')
                    ->required()
                    ->autocomplete(false)
                    ->columnSpan(3)
                    ->exists(table: Puc::class, column: 'puc')
                    ->validationMessages([
                        'exists' => 'El :attribute no existe en Plan Unico de cuentas.',
                    ])
                    ->readOnly()
                    ->live()
                    ->maxLength(8),
                Select::make('naturaleza')
                    ->required()
                    ->columnSpan(2)
                    ->label('Naturaleza de la cuenta')
                    ->options([
                        'D' => 'Debito',
                        'C' => 'Credito ',
                    ]),

                    Toggle::make('mayor_rep')
                        ->required()
                        ->label('Cuenta mayor?')
                        ->reactive()
                        ->columnSpan(4)
                        ->afterStateUpdated(function (callable $set, callable $get, $state) {
                                if ($state) {
                                    // Desactivar y poner en false todos los demás campos
                                    $set('movimiento', false);
                                    $set('subcentro', false);
                                    $set('bancaria', false);
                                    $set('tercero', false);
                                    $set('base_gravable', false);
                                    $set('mueve_modulo', false);
                                    $set('codigo_dian', false);

                                    // También debes desactivarlos
                                    $set('movimiento_disabled', true);
                                    $set('subcentro_disabled', true);
                                    $set('bancaria_disabled', true);
                                    $set('tercero_disabled', true);
                                    $set('base_gravable_disabled', true);
                                    $set('mueve_modulo_disabled', true);
                                    $set('codigo_dian_disabled', true);
                                } else {
                                    // Reactivar los demás campos si 'mayor_rep' se desactiva
                                    $set('movimiento_disabled', false);
                                    $set('subcentro_disabled', false);
                                    $set('bancaria_disabled', false);
                                    $set('tercero_disabled', false);
                                    $set('base_gravable_disabled', false);
                                    $set('mueve_modulo_disabled', false);
                                    $set('codigo_dian_disabled', false);
                                }
                            }),

                Toggle::make('movimiento')
                    ->required()
                    ->label('Cuenta permite movimiento?')
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('movimiento_disabled'))
                    ->afterStateUpdated(function (callable $set, callable $get, $state) {
                        if ($state) {
                            $set('mayor_rep', false); // Desactivar 'mayor_rep'
                        }
                    }),
                Toggle::make('subcentro')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('subcentro_disabled'))
                    ->label('Cuenta se maneja por subcentro?'),
                Toggle::make('bancaria')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('bancaria_disabled'))
                    ->label('Es cuenta bancaria?'),
                Toggle::make('tercero')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('tercero_disabled'))
                    ->label('Cuenta requiere adminisracion por Terceros?'),
                Toggle::make('base_gravable')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('base_gravable_disabled'))
                    ->label('Cuenta solicita base gravable?'),
                Toggle::make('mueve_modulo')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->disabled(fn (callable $get) => $get('mueve_modulo_disabled'))
                    ->label('Es cuenta de conciliacion?'),
                Toggle::make('codigo_dian')
                    ->required()
                    ->reactive()
                    ->columnSpan(4)
                    ->live(onBlur: true)
                    ->disabled(fn (callable $get) => $get('codigo_dian_disabled'))
                    ->label('Es cuenta que reporta a la DIAN?'),
                TextInput::make('informe_exog')
                    ->label('Informe Exogena')
                    ->hidden(fn (callable $get) => !$get('codigo_dian'))
                    ->required(fn (callable $get) => $get('codigo_dian')) // Requerido solo si el toggle está activo
                    ->reactive()
                    ->columnSpan(2)
                    ->live()
                    ->rules(['required', 'string', 'max:4']),
                TextInput::make('concepto_exog')
                    ->label('Concepto Exogena')
                    ->hidden(fn (callable $get) => !$get('codigo_dian'))
                    ->required(fn (callable $get) => $get('codigo_dian')) // Requerido solo si el toggle está activo
                    ->reactive()
                    ->columnSpan(1)
                    ->live()
                    ->rules(['required', 'string', 'max:4']),
                TextInput::make('descripcion_concepto_exog')
                    ->label('Descripcion del Concepto Exogena')
                    ->hidden(fn (callable $get) => !$get('codigo_dian'))
                    ->required(fn (callable $get) => $get('codigo_dian')) // Requerido solo si el toggle está activo
                    ->reactive()
                    ->columnSpan(3)
                    ->live()
                    ->rules(['required', 'string', 'max:50'])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('puc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
            ])
            ->defaultSort('puc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPucs::route('/'),
            'create' => Pages\CreatePuc::route('/create'),
            'edit' => Pages\EditPuc::route('/{record}/edit'),
        ];
    }
}
