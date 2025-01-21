<?php

namespace App\Filament\Resources\GestionAsociadoResource\RelationManagers;

use App\Models\CarteraEncabezado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class CuotasRelationManager extends RelationManager
{
    protected static string $relationship = 'cuotas';
    protected static ?string $title = 'Cartera';
    protected static bool $isLazy = false;

    public function getContentTabIcon(): ?string
    {
        return 'heroicon-m-cog';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('cliente')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('estado', 'A')->where('tdocto', 'PAG'))
            ->recordTitleAttribute('cliente')
            ->columns([
                Tables\Columns\TextColumn::make('nro_docto'),
                Tables\Columns\TextColumn::make('nro_cuotas')->label('Nro cuotas'),
                Tables\Columns\TextColumn::make('lineaCredito.descripcion'),
                Tables\Columns\TextColumn::make('nro_dias_mora'),
                Tables\Columns\TextColumn::make('vlr_saldo_actual')->label('Saldo actual')
                    ->money('COP'),
                Tables\Columns\TextColumn::make('created_at')->label('Fecha de desembolso'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make()->label('Gestionar'),
                Action::make('preliquidacion')->label('Pre-liquidación')
                    ->hiddenLabel()
                    ->iconSize('md')
                    ->color('info')
                    ->icon('heroicon-s-clipboard-document-list')
                    ->modalContent(function (CarteraEncabezado $record) {
                        return view('custom.credito_solicitudes.preliquidacion_table', ['nro_documento' => $record->nro_docto, 'tipo_documento' => 'PAG']);
                    })
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
                Action::make('estado_credito')->label('Estado de credito')
                    ->modalContent(function (CarteraEncabezado $record) {
                        return view('custom.credito_solicitudes.preliquidacion_table', ['nro_documento' => $record->nro_docto, 'tipo_documento' => 'PAG', 'estado' => true]);
                    })
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false),
                Action::make('obligaciones')
                    ->icon('heroicon-s-document-currency-dollar')
                    ->iconSize('md')
                    ->color('warning')
                    ->hiddenLabel()
                    ->modalContent(function (CarteraEncabezado $record) {
                        //dd($record->nro_docto);
                        return view('custom.credito_solicitudes.obligaciones_table', ['nro_documento' => $record->nro_docto]);
                    })
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }
}
