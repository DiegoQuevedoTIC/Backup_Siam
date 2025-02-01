<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1012Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1012Resource\RelationManagers;
use App\Models\Exogena1012;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\Informe1012Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class Exogena1012Resource extends Resource
{
    protected static ?string $model = Exogena1012::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1012 - Aportes y Inversiones';
    protected static ?string $modelLabel = '1012 - Aportes y Inversiones';

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
            ->heading('Formato 1012: Información de declaraciones tributarias, acciones, inversiones en bonos, cuentas de ahorro y corrientes')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1012: Información de declaraciones tributarias, acciones, inversiones en bonos, cuentas de ahorro y corrientes')
            ->emptyStateDescription('Este formato abarca información sobre declaraciones tributarias, así como inversiones en acciones, bonos, y saldos en cuentas de ahorro y corrientes.
            Se debe detallar el tipo de inversión o cuenta, entidad financiera, número de cuenta o identificación de la inversión, y saldos o valores correspondientes.')
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1012Exporter::class)
                    ->form([
                        DatePicker::make('fecha_corte')
                            ->label('Fecha de Corte')
                            ->required(),
                    ])
                    ->columnMapping(false)
                    ->label('Generar Informe')
            ])
            ->actions([]);

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
            'index' => Pages\ListExogena1012s::route('/'),
            'create' => Pages\CreateExogena1012::route('/create'),
            'edit' => Pages\EditExogena1012::route('/{record}/edit'),
        ];
    }
}
