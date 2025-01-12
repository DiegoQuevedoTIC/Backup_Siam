<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ParametrosCartera;
use App\Filament\Resources\CreditoSolicitudResource\Pages;
use App\Filament\Resources\CreditoSolicitudResource\RelationManagers;
use App\Filament\Resources\CreditoSolicitudResource\RelationManagers\CuotasEncabezadosRelationManager;
use App\Filament\Resources\CreditoSolicitudResource\RelationManagers\LineaRelationManager;
use App\Models\CreditoSolicitud;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CreditoSolicitudResource extends Resource
{
    protected static ?string $model = CreditoSolicitud::class;
    protected static ?string $cluster = ParametrosCartera::class;


    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationLabel = 'Aprobaciones de Solicitudes';
    protected static ?string $modelLabel = 'Aprobaciones de Solicitudes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lineaCredito.descripcion')
                    ->label('Linea')
                    ->disabled(),
                Forms\Components\TextInput::make('empresaCredito.nombre')
                    ->disabled(),
                Forms\Components\TextInput::make('nro_cuotas_gracia')
                    ->disabled(),
                Forms\Components\TextInput::make('tipo_desembolso')
                    ->disabled(),
                Forms\Components\TextInput::make('tercero_asesor')
                    ->disabled(),
                Forms\Components\TextInput::make('observaciones')
                    ->disabled(),
                Forms\Components\DatePicker::make('fecha_primer_vto')
                    ->disabled(),
                Forms\Components\TextInput::make('tasa_id')
                    ->disabled(),
                Forms\Components\TextInput::make('vlr_solicitud')
                    ->required(),
                Forms\Components\DatePicker::make('fecha_solicitud')
                    ->disabled(),
                Forms\Components\Textarea::make('nro_cuotas_max')
                    ->disabled()
                    ->columnSpanFull(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Solicitud')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('linea')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('asociado')
                    ->searchable(),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'P' => 'gray',
                        'N' => 'danger',
                        'M' => 'gray',
                        'A' => 'success',
                        'C' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'P' => 'PENDIENTE',
                        'N' => 'NEGADA',
                        'M' => 'MONTO DESEMBOLSO',
                        'A' => 'APROBADA',
                        'C' => 'CANCELADA',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_solicitud')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vlr_solicitud')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()->label('Gestionar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->where('estado', '=', 'P'))
            ->defaultSort('fecha_solicitud', 'desc');
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCreditoSolicituds::route('/'),
            'create' => Pages\CreateCreditoSolicitud::route('/create'),
            'view' => Pages\ViewCreditoSolicitud::route('/{record}'),
            'edit' => Pages\EditCreditoSolicitud::route('/{record}/edit'),
        ];
    }
}
