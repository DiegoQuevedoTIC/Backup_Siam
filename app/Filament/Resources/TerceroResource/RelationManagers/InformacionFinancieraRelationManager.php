<?php

namespace App\Filament\Resources\TerceroResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Support\Str;

class InformacionFinancieraRelationManager extends RelationManager
{
    protected static string $relationship = 'InformacionFinanciera';


    public function form(Form $form): Form
    {
        return $form
            ->columns(9)
            ->schema([
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $pasivos = $get('total_pasivos'); // Obtener el valor actual de total_pasivos
                        $set('total_patrimonio', $state - $pasivos);
                    })
                    ->label('Total Activos'),
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $activos = $get('total_activos'); // Obtener el valor actual de total_activos
                        $set('total_patrimonio', $activos - $state);
                    })
                    ->label('Total Pasivos'),

                TextInput::make('total_patrimonio')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->readOnly()
                    ->live(onBlur: true)
                    ->columnSpan(3)
                    ->inputMode('decimal')
                    ->hint('Total Patrimonio')
                    ->hintColor('primary')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.',  precision: 2)
                    ->label(''),

                TextInput::make('salario')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->autocomplete(false)
                    ->markAsRequired(false)
                    ->maxLength(15)
                    ->columnSpan(2)
                    ->inputMode('decimal')
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_ingresos', $state + $get('honorarios') + $get('otros_ingresos'));
                    })
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_ingresos', $get('salario') + $state + $get('otros_ingresos'));
                    })
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_ingresos', $get('salario') + $get('honorarios') + $state);
                    })
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->minValue(0)
                    ->label('Otros Ingresos'),

                TextInput::make('total_ingresos')
                    ->prefix('$')
                    ->numeric()
                    ->required()
                    ->columnSpan(3)
                    ->readonly()
                    ->maxLength(15)
                    ->inputMode('decimal')
                    ->hint('Total Ingresos')
                    ->hintColor('primary')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->label('')
                    ->default(0),

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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_gastos', $get('gastos_financieros') + $get('otros_gastos') + $state);
                    })
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_gastos', $get('gastos_sostenimiento') + $get('otros_gastos') + $state);
                    })
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
                    ->afterStateUpdated(function (Set $set, $state, $get) {
                        $set('total_gastos', $get('gastos_sostenimiento') + $get('gastos_financieros') + $state);
                    })
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
                    ->readonly()
                    ->inputMode('decimal')
                    ->hint('Total Gastos')
                    ->hintColor('primary')
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->live(onBlur: true)
                    ->label(''),

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
