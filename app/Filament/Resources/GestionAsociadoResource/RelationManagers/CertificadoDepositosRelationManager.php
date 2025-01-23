<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;;

use App\Models\Asesor;
use App\Models\Barrio;
use App\Models\Beneficiario;
use App\Models\CertificadoDeposito;
use App\Models\Ciudad;
use App\Models\Profesion;
use App\Models\Prorroga;
use App\Models\Tercero;
use App\Models\TipoIdentificacion;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action as ActionsTable;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;

class CertificadoDepositosRelationManager extends RelationManager
{
    protected static string $relationship = 'certificadoDepositos';

    protected static ?string $title = 'CDATs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Constituir CDAT')
                    ->description('Creación de registro')
                    ->icon('heroicon-m-user')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')->default('admin'),

                        Forms\Components\TextInput::make('numero_cdt')->label('Número CDT')->required()->autocomplete(false)->rule('regex:/^[0-9]+$/'),
                        Forms\Components\TextInput::make('plazo')->label('Plazo de inversión')->required()->autocomplete(false)->live(),
                        Forms\Components\TextInput::make('valor')->label('Valor Inical')->required()->numeric()->autocomplete(false),
                        Forms\Components\DatePicker::make('fecha_creacion')->label('Fecha Creacion')->required()->live(),
                        Forms\Components\DatePicker::make('fecha_vencimiento')->label('Fecha Vencimiento')->live(),
                        Forms\Components\TextInput::make('tasa_ea')->label('Tasa remuneración')->required()->autocomplete(false),
                        Forms\Components\TextInput::make('tasa_interes')->label('Tasa remuneración')->required()->autocomplete(false),
                        Forms\Components\TextInput::make('observaciones')->label('Observaciones')->required()->autocomplete(false),
                        Forms\Components\Hidden::make('estado')->default('A'),
                        Forms\Components\Hidden::make('contabilizado')->default(true)




                    ])->columns(3),

                Actions::make([
                    Action::make('beneficiarios')
                        ->label('Beneficiarios')
                        ->color('info')
                        ->form([
                            Section::make('Constituir CDAT | Creación de beneficiarios')
                            ->description('Creación de Beneficiario')
                            ->icon('heroicon-m-users')
                            ->schema([
                                TextInput::make('nro_identi_beneficiario')->label('Nro Identificación Beneficiario')->required()->autocomplete(false),
                                TextInput::make('nombre_beneficiario')->label('Nombre beneficiario')->required()->autocomplete(false),
                                TextInput::make('porcentaje_titulo')->label('Porcentaje Titulo')->required()->autocomplete(false),
                                Textarea::make('observaciones')->label('Observaciones')->required()->columnSpanFull()->autocomplete(false)
                            ])->columns(2)
                        ])
                        ->action(fn (array $data) => [
                            Beneficiario::create([
                                'asociado_id' => $this->getOwnerRecord()->id,
                                'nro_identi_beneficiario' => $data['nro_identi_beneficiario'],
                                'nombre_beneficiario' => $data['nombre_beneficiario'],
                                'porcentaje_titulo' => $data['porcentaje_titulo'],
                                'observaciones' => $data['observaciones']
                            ]),

                            Notification::make()
                            ->title('Se registraron los datos correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('Los datos fueron registrados correctamente')
                            ->success()
                            ->send()
                        ]),
                    Action::make('prorroga_cdat')
                        ->label('Prorroga CDAT')
                        ->color('info')
                        ->form([
                            Section::make('Constituir CDAT | Creación de prorroga')
                            ->description('Creación de prorroga')
                            ->icon('heroicon-m-clipboard-document-list')
                            ->schema([
                                TextInput::make('plazo_inversion')->label('Plazo de inversión')->required()->autocomplete(false),
                                TextInput::make('valor_inicial_cdat')->label('Valor Inicial')->numeric()->required()->autocomplete(false),
                                TextInput::make('valor_prorroga')->label('Valor prorroga')->numeric()->required()->autocomplete(false),
                                TextInput::make('tasa_interes_remuneracion')->label('Tasa interes')->required()->autocomplete(false),
                                TextInput::make('porcentaje_retencion')->label('Procentaje de retención')->required()->autocomplete(false)
                            ])->columns(2)
                        ])
                        ->action(fn (array $data) => [
                            Prorroga::create([
                                'asociado_id' => $this->getOwnerRecord()->id,
                                'plazo_inversion' => $data['plazo_inversion'],
                                'valor_inicial_cdat' => $data['valor_inicial_cdat'],
                                'valor_prorroga' => $data['valor_prorroga'],
                                'tasa_interes_remuneracion' => $data['tasa_interes_remuneracion'],
                                'porcentaje_retencion' => $data['porcentaje_retencion'],
                            ]),

                            Notification::make()
                            ->title('Se registraron los datos correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('Los datos fueron registrados correctamente')
                            ->success()
                            ->send()
                        ]),
                ])->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tasa')
            ->columns([
                Tables\Columns\TextColumn::make('numero_cdt')->label('Número CDT'),
                Tables\Columns\TextColumn::make('valor')->label('Valor'),
                Tables\Columns\TextColumn::make('plazo')->label('Plazo'),
                Tables\Columns\TextColumn::make('tasa_ea')->label('Tasa EA'),
                Tables\Columns\TextColumn::make('fecha_creacion')->label('Fecha Creación'),
                Tables\Columns\TextColumn::make('fecha_vencimiento')->label('Fecha Vencimiento'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->slideOver()->label('Constituir CDAT'),
                ActionsTable::make('actualizar_datos')->label('Actualización Datos')
                    ->fillForm(fn (): array => [
                        $tercero = Tercero::find($this->getOwnerRecord()->id),
                        'nro_identificacion' => $tercero->tercero_id,
                        'nombres' => $tercero->nombres,
                        'primer_apellido' => $tercero->primer_apellido,
                        'segundo_apellido' => $tercero->segundo_apellido,
                        'tipo_documento' => $tercero->TipoIdentificacion->nombre,
                        'ocupacion' => $tercero->profesion_id,
                        'direccion' => $tercero->direccion,
                        'barrio' => $tercero->barrio_id,
                        'ciudad' => $tercero->ciudad_id,
                        'nro_celular_1' => $tercero->celular,
                        'nro_celular_2' => $tercero->nro_celular_2,
                        'nro_telefono_fijo' => $tercero->telefono,
                        'correo' => $tercero->email,
                        'total_activos' => $tercero->InformacionFinanciera->total_activos ?? 0,
                        'total_pasivos' => $tercero->InformacionFinanciera->total_pasivos ?? 0,
                        'salario' => $tercero->InformacionFinanciera->salario ?? 0,
                        'honorarios' => $tercero->InformacionFinanciera->honorarios ?? 0,
                        'gastos_financieros' => $tercero->InformacionFinanciera->gastos_financieros ?? 0,
                        'creditos_hipotecarios' => $tercero->InformacionFinanciera->creditos_hipotecarios ?? 0,
                        'otros_gastos' => $tercero->InformacionFinanciera->otros_gastos ?? 0,
                    ])
                    ->form([
                        Section::make('Actualización de Datos')
                            ->description('Tercero Natural')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Forms\Components\TextInput::make('nro_identificacion')->label('Nro Identificación')->disabled()->autocomplete(false),
                                Forms\Components\TextInput::make('nombres')->label('Nombre')->required()->autocomplete(false),
                                Forms\Components\TextInput::make('primer_apellido')->label('Primer Apellido')->required()->autocomplete(false),
                                Forms\Components\TextInput::make('segundo_apellido')->label('Segundo Nombre')->required()->autocomplete(false),
                                Forms\Components\Select::make('tipo_documento')->label('Tipo de Documento')->required()
                                    ->options(TipoIdentificacion::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\Select::make('ocupacion')->label('Ocupación')->required()
                                    ->options(Profesion::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('direccion')->label('Dirección')->required()->autocomplete(false),
                                Forms\Components\Select::make('barrio')->label('Barrio')->required()
                                    ->options(Barrio::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\Select::make('ciudad')->label('Ciudad')->required()
                                    ->options(Ciudad::all()->pluck('nombre', 'id'))
                                    ->searchable(),
                                Forms\Components\TextInput::make('nro_celular_1')->label('Nro Celular 1')->required()->autocomplete(false),
                                Forms\Components\TextInput::make('nro_celular_2')->label('Nro Celular 2')->autocomplete(false),
                                Forms\Components\TextInput::make('nro_telefono_fijo')->label('Telefono Fijo')->required()->autocomplete(false),
                                Forms\Components\TextInput::make('correo')->label('Correo')->required()->autocomplete(false),
                            ])->columns(3),
                        Section::make('Datos Financieros')
                            ->description('Aqui debes actualizar los datos financieros, de lo contrario no se modifica nada')
                            ->icon('heroicon-m-wallet')
                            ->schema([
                                Forms\Components\TextInput::make('total_activos')->label('Total Activos')->mask('9999999,99')->autocomplete(false),
                                Forms\Components\TextInput::make('total_pasivos')->label('Total Pasivos')->mask('9999999,99')->autocomplete(false),
                                Forms\Components\TextInput::make('salario')->label('Salario')->mask('9999999.99')->autocomplete(false),
                                Forms\Components\TextInput::make('honorarios')->label('Honorarios')->mask('9999999,99')->autocomplete(false),
                                Forms\Components\TextInput::make('gastos_financieros')->label('Gastos Financieros')->mask('9999999,99')->autocomplete(false),
                                Forms\Components\TextInput::make('creditos_hipotecarios')->label('Credito Hipotecario')->mask('9999999,99')->autocomplete(false),
                                Forms\Components\TextInput::make('otros_gastos')->label('Otros Gastos')->mask('9999999,99')->autocomplete(false),
                            ])->columns(3),
                    ])->action(function (array $data): void {

                        $tercero = Tercero::find($this->getOwnerRecord()->id);

                        $tercero->update([
                            'nombres' => $data['nombres'],
                            'primer_apellido' => $data['primer_apellido'],
                            'segundo_apellido' => $data['segundo_apellido'],
                            'direccion' => $data['direccion'],
                            'telefono' => $data['nro_telefono_fijo'],
                            'celular' => $data['nro_celular_1'],
                            'email' => $data['correo'],
                            'ciudad_id' => $data['ciudad'],
                            'barrio_id' => $data['barrio'],
                            'profesion_id' => $data['ocupacion'],
                        ]);

                        Notification::make()
                            ->title('Se actualizaron los datos correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('Los datos fueron actualizados correctamente')
                            ->success()
                            ->send();
                    })->slideOver()
                    ->modalSubmitActionLabel('Actualizar'),
                ActionsTable::make('plan_de_pago_cdat')->label('Plan de pago CDAT')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
    }
}
