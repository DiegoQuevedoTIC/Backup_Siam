<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GestionAsociadoResource\Pages;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\AportesRelationManager;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\CertificadoDepositosRelationManager;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\CobranzasRelationManager;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\CreditoSolicitudesRelationManager;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\GarantiasRelationManager;
use App\Filament\Resources\GestionAsociadoResource\RelationManagers\ObligacionesRelationManager;
use App\Models\Asociado;
use App\Models\Tercero;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Set;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class GestionAsociadoResource extends Resource
{
    protected static ?string $model = Asociado::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Estado de Cuenta';
    protected static ?string $navigationGroup = 'Gestión de Asociados';
    protected static ?string $modelLabel = 'Estado de cuenta Asociado';
    protected static ?string $pluralModelLabel = 'Estado de cuenta Asociado';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('tercero.tercero_id')
                    ->searchable()
                    ->label('Identificacion'),
                Tables\Columns\TextColumn::make('tercero.nombres')
                    ->searchable()
                    ->label('Nombres'),
                Tables\Columns\TextColumn::make('tercero.primer_apellido')
                    ->searchable()
                    ->label('Primer Apellido'),
                Tables\Columns\TextColumn::make('tercero.segundo_apellido')
                    ->searchable()
                    ->label('Segundo Apellido'),
                Tables\Columns\TextColumn::make('EstadoCliente.nombre')
                    ->searchable()
                    ->label('Estado Actual'),
                Tables\Columns\TextColumn::make('pagaduria.nombre')
                    ->searchable()
                    ->label('Pagaduria'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
            CreditoSolicitudesRelationManager::class,
            ObligacionesRelationManager::class,
            AportesRelationManager::class,
            CertificadoDepositosRelationManager::class,
            GarantiasRelationManager::class,
            CobranzasRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGestionAsociados::route('/'),
            'create' => Pages\CreateGestionAsociado::route('/create'),
            'edit' => Pages\EditGestionAsociado::route('/{record}/edit'),
            'view' => Pages\ViewAsociado::route('/{record}'),
        ];
    }
}
