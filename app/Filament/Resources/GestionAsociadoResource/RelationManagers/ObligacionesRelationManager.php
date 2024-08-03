<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\CuotaDescuento;
use App\Models\HistoricoDescuento;
use App\Models\Obligacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action as ActionsTable;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ObligacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'obligaciones';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('concepto')
            ->columns([
                Tables\Columns\TextColumn::make('concepto')->label('Concepto Obligación')->default('N/A'),
                Tables\Columns\TextColumn::make('valor_descuento')->label('Valor Obligación')->default('N/A'),
                Tables\Columns\TextColumn::make('fecha_ultima_couta')->label('Fecha limite Pago')->default('N/A'),
                Tables\Columns\TextColumn::make('nro_cuota')->label('Nro de Cuota')->default('N/A'),
                Tables\Columns\TextColumn::make('fecha_ultima_couta')->label('Limite de Cuotas')->default('N/A'),
            ])
            ->filters([
                //
                Filter::make('vigente'),
                Filter::make('vencida'),
            ])
            ->headerActions([
                ActionsTable::make('programar_obligacion')->label('Programar Obligación')
                    ->form([
                        Section::make('Concepto Obligación')
                            ->description('Definir texto de ejemplo')
                            ->schema([
                                // ...
                                Forms\Components\TextInput::make('aportes')->required(),
                                Forms\Components\TextInput::make('valor_descuento')->required()->numeric(),
                                Forms\Components\Checkbox::make('definido')->default(false)->live(),
                                Forms\Components\Checkbox::make('indefinido')->default(false)->live(),
                                Forms\Components\TextInput::make('plazo')->label('Plazo')->numeric()
                                    ->visible(function (Get $get) {
                                        $indefinido = $get('indefinido');

                                        if ($indefinido) {
                                            return false;
                                        }

                                        return true;
                                    })->live(),
                                Forms\Components\DatePicker::make('fecha_inicio_descuento'),
                                Forms\Components\TextInput::make('periodo_descuento'),
                            ])->columns(4),
                        Section::make('Historico Obligación')
                            ->description('Definir texto de ejemplo')
                            ->schema([
                                // ...
                                Forms\Components\TextInput::make('concepto')->required(),
                                Forms\Components\TextInput::make('nro_cuota')->numeric(),
                                Forms\Components\Checkbox::make('vigente'),
                                Forms\Components\Checkbox::make('vencida'),
                                Forms\Components\DatePicker::make('fecha_ultima_couta'),
                            ])->columns(4),
                    ])->action(function (array $data) {
                        // inicialización de transacion para garantizar integridad de datos
                        DB::transaction(function () use ($data) {

                            // Creamos la solicitud
                            $obligacion = Obligacion::create([
                                'asociado_id' => $this->getOwnerRecord()->id,
                                'concepto' => $data['concepto'],
                                'valor_descuento' => $data['valor_descuento'],
                                'plazo' => $data['plazo'],
                                'fecha_inicio_descuento' => $data['fecha_inicio_descuento'],
                                'periodo_descuento' => $data['periodo_descuento'],
                                'nro_cuota' => $data['nro_cuota'],
                                'vigente' => $data['vigente'],
                                'vencida' => $data['vencida'],
                                'fecha_ultima_couta' => $data['fecha_ultima_couta'],
                                'aportes' => $data['aportes'],
                            ]);

                            // Creamos la primera cuota
                            $cuota_descuento = CuotaDescuento::create([
                                'cliente' => $this->getOwnerRecord()->codigo_interno_pag,
                                'con_descuento' => $data['valor_descuento'] ? true : false,
                                'consecutivo' => null,
                                'nro_cuota' => $data['nro_cuota'],
                                'fecha_vencimiento' => 'N/A',
                                'fecha_pago_total' => $data['fecha_ultima_couta'],
                                'estado' => 'C',
                                'vlr_cuota' => $data['valor_descuento'],
                                'abono_cuota' => '0',
                                'vlr_interes' => 'o',
                                'abono_interes' => '0',
                                'vlr_mora' => '0',
                                'abono_mora' => '0',
                                'congelada' => null,
                                'consecutivo_padre' => '0',
                            ]);

                            // creamos el historico de obligacion
                            $historico = HistoricoDescuento::create([
                                'cliente' => $this->getOwnerRecord()->codigo_interno_pag,
                                'con_descuento' => $data['valor_descuento'] ? true : false,
                                'linea' => null,
                                'con_servicio' => $data['aportes'] ? true : false,
                                'fecha' => $data['fecha_inicio_descuento'],
                                'hora' => null,
                                'grupo_docto' => $data['concepto'],
                                'compania_docto' => $data['concepto'],
                                'agencia_docto' => $data['concepto'],
                                'tdocto' => $data['concepto'],
                                'nro_docto' => $data['concepto'],
                                'vlr_debito' => $data['valor_descuento'],
                                'vlr_credito' => $data['valor_descuento'],
                            ]);

                            Notification::make()
                                ->title('Se crearon los datos correctamente')
                                ->icon('heroicon-m-check-circle')
                                ->body('Los datos fueron creados correctamente')
                                ->success()
                                ->send();
                        }, 5);
                    })
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                /*  Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
    }
}
