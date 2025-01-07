<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CreditoSolicitudResource\Pages;
use App\Filament\Resources\CreditoSolicitudResource\RelationManagers;
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
    protected static ?string $navigationGroup = 'Gestión de Asociados';

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationLabel = 'Lineas de credito';
    protected static ?string $modelLabel = 'Linea de credito';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('solicitud')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('linea')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('asociado')
                    ->maxLength(255),
                Forms\Components\TextInput::make('estado')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_solicitud'),
                Forms\Components\Textarea::make('observacion_novedad')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('solicitud')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_solicitud', 'desc');
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
            'index' => Pages\ListCreditoSolicituds::route('/'),
            'create' => Pages\CreateCreditoSolicitud::route('/create'),
            'view' => Pages\ViewCreditoSolicitud::route('/{record}'),
            'edit' => Pages\EditCreditoSolicitud::route('/{record}/edit'),
        ];
    }
}
