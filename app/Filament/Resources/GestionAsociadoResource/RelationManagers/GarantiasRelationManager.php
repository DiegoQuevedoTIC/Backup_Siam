<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\Garantia;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\Alignment;

class GarantiasRelationManager extends RelationManager
{
    protected static string $relationship = 'garantias';

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
            ->recordTitleAttribute('altura_mora')
            ->columns([
                Tables\Columns\TextColumn::make('tipo_garantia_id')->label('Tipo garantia')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'P' => 'PERSONAL',
                        'R' => 'REAL',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'P' => 'info',
                        'R' => 'primary',
                    })
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('tercero.nombre_completo')->label('Tercero garantia')->default('N/A'),
                Tables\Columns\TextColumn::make('estado')->label('Estado garantia')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'A' => 'ACTIVO',
                        'C' => 'CANCELADA',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'A' => 'primary',
                        'C' => 'danger',
                    })
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('nro_escr_o_matri')
                    ->label('Nro escritura / Matrícula')
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('fecha_avaluo')
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('valor_avaluo')->label('Valor avaluo')->money('COP')
                    ->alignment(Alignment::End)
                    ->default(0),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Crear Garantia')->form([
                    Forms\Components\Select::make('tipo_garantia_id')
                        ->label('Tipo de garantía')
                        ->options([
                            'R' => 'Garantia Real',
                            'P' => 'Garantia Personal'
                        ])
                        ->searchable()
                        ->required()
                        ->reactive(),
                    Forms\Components\TextInput::make('nro_escr_o_matri')
                        ->label('Nro escritura / Matrícula')
                        ->required()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\TextInput::make('direccion')
                        ->label('Dirección')
                        ->required()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\TextInput::make('ciudad_registro')
                        ->label('Ciudad Registro')
                        ->required()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\TextInput::make('valor_avaluo')
                        ->label('Valor Avaluo')
                        ->required()
                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                        ->inputMode('decimal')
                        ->prefix('$')
                        ->numeric()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\DatePicker::make('fecha_avaluo')
                        ->label('Fecha Avaluo')
                        ->required()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\Checkbox::make('bien_con_prenda')
                        ->label('Bien con prenda')
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\Checkbox::make('bien_sin_prenda')
                        ->label('Bien sin prenda')
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    Forms\Components\TextInput::make('valor_avaluo_comercial')
                        ->label('Valor Avaluo Comercial')
                        ->required()
                        ->numeric()
                        ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                        ->inputMode('decimal')
                        ->prefix('$')
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'R'),

                    // Campos para garantía "personal"
                    Forms\Components\Select::make('tercero_garantia')
                        ->label('Tercero Garante')
                        ->searchable()
                        ->visible(fn(callable $get) => $get('tipo_garantia_id') === 'P')
                        ->options(fn() => Tercero::query()
                            ->select(DB::raw("tercero_id, CONCAT(nombres, ' ', primer_apellido) AS nombre_completo"))
                            ->pluck('nombre_completo', 'tercero_id'))
                        ->required(),


                    // Campos comunes
                    Forms\Components\TextInput::make('observaciones')
                        ->label('Observaciones')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                ])
                    ->action(function (array $data) {
                        Garantia::create([
                            'asociado_id' => $this->getOwnerRecord()->codigo_interno_pag,
                            'tipo_garantia_id' => $data['tipo_garantia_id'] ?? null,
                            'nro_escr_o_matri' => $data['nro_escr_o_matri'] ?? null,
                            'direccion' => $data['direccion'] ?? null,
                            'ciudad_registro' => $data['ciudad_registro'] ?? null,
                            'valor_avaluo' => $data['valor_avaluo'] ?? 0.00,
                            'fecha_avaluo' => $data['fecha_avaluo'] ?? null,
                            'bien_con_prenda' => $data['bien_con_prenda'] ?? false,
                            'bien_sin_prenda' => $data['bien_sin_prenda'] ?? false,
                            'valor_avaluo_comercial' => $data['valor_avaluo'] ?? 0.00,
                            'observaciones' => $data['observaciones'] ?? null,
                            'tercero_garantia' => $data['tercero_garantia'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Garantía creada correctamente')
                            ->icon('heroicon-m-check-circle')
                            ->body('La garantía fue creada correctamente')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                /* Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]), */]);
    }
}
