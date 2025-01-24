<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Date;

class InformacionFinancieraRelationManager extends RelationManager
{
    protected static string $relationship = 'InformacionFinanciera';

    /**
     * Define the form for the relation manager.
     */
    public function form(Form $form): Form
    {
        return $form
            ->columns(9) // Define la cantidad de columnas
            ->schema([
                // Activos
                TextInput::make('total_activos')
                    ->numeric()
                    ->prefix('$')
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(3)
                    ->minValue(0)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->label('Total Activos'),

                // Pasivos
                TextInput::make('total_pasivos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->minValue(0)
                    ->label('Total Pasivos'),

                // Patrimonio (Calculado automáticamente)
                TextInput::make('total_patrimonio')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->maxLength(15)
                    ->live(onBlur: true)
                    ->columnSpan(3)
                    ->minValue(0)
                    ->readonly()
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->label('Total Patrimonio')
                    ->afterStateUpdated(function (Get $get, Set $set): bool {
                        $total_activos = $get('total_activos') ?? 0;
                        $total_pasivos = $get('total_pasivos') ?? 0;
                        $set('total_patrimonio', $total_activos - $total_pasivos);
                        return true;
                    }),

                // Ingresos
                TextInput::make('salario')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->minValue(0)
                    ->label('Salario'),

                TextInput::make('honorarios')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->minValue(0)
                    ->label('Honorarios'),

                TextInput::make('otros_ingresos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->minValue(0)
                    ->label('Otros Ingresos'),

                TextInput::make('total_ingresos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->columnSpan(3)
                    ->maxLength(15)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->label('Total Ingresos')
                    ->default(0)
                    ->readonly()
                    ->afterStateUpdated(function (Get $get, Set $set): bool {
                        $salario = $get('salario') ?? 0;
                        $honorarios = $get('honorarios') ?? 0;
                        $otros_ingresos = $get('otros_ingresos') ?? 0;
                        $set('total_ingresos', $salario + $honorarios + $otros_ingresos);
                        return true;
                    }),

                // Gastos
                TextInput::make('gastos_sostenimiento')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->live(onBlur: true)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->minValue(0)
                    ->label('Gastos Sostenimiento'),

                TextInput::make('gastos_financieros')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->minValue(0)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->label('Gastos Financieros'),

                TextInput::make('otros_gastos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->minValue(0)
                    ->label('Otros Gastos'),

                TextInput::make('total_gastos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(3)
                    ->minValue(0)
                    ->inputMode('decimal')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->label('Total Gastos')
                    ->disabled(function (Get $get, Set $set): bool {
                        $gastos_sostenimiento = $get('gastos_sostenimiento') ?? 0;
                        $gastos_financieros = $get('gastos_financieros') ?? 0;
                        $otros_gastos = $get('otros_gastos') ?? 0;
                        $set('total_gastos', $gastos_sostenimiento + $gastos_financieros + $otros_gastos);
                        return true;
                    }),

                // Créditos Hipotecarios (Alineado a la izquierda con formato)
                TextInput::make('creditos_hipotecarios')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->inputMode('decimal')
                    ->columnSpan(4)
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->prefix('$')
                    ->minValue(0)
                    ->label('Créditos Hipotecarios'),
            ]);
    }

    /**
     * Define the table for the relation manager.
     */
    public function table(Table $table): Table
    {
        return $table
        ->paginated(false)
        ->columns([
            Tables\Columns\TextColumn::make('total_activos')
            ->formatStateUsing(fn ($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
            ->label('Total Activos'),
            Tables\Columns\TextColumn::make('total_pasivos')
            ->formatStateUsing(fn ($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
            ->label('Total Pasivos'),
            Tables\Columns\TextColumn::make('total_patrimonio')
            ->formatStateUsing(fn ($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
            ->label('Total Patrimonio'),
            Tables\Columns\TextColumn::make('total_ingresos')
            ->formatStateUsing(fn ($state) => $state !== null ? '$' . number_format($state, 2, '.', ',') : '$0.00')
            ->label('Total Ingresos'),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Última Actualización')
                ->dateTime('d/m/Y H:i'),
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
            ->emptyStateHeading('Agregar Información Financiera')
            ->emptyStateDescription('En este módulo podrás gestionar de forma sencilla la información financiera.');
    }
}
