<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1009Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1009Resource\RelationManagers;
use App\Models\Exogena1009;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\Informe1009Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class Exogena1009Resource extends Resource
{
    protected static ?string $model = Exogena1009::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1009 - Saldos de Cuentas por Pagar';
    protected static ?string $modelLabel = '1009 - Saldos de Cuentas por Pagar';

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
            ->heading('Formato 1009: Saldos de cuentas por pagar')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)

            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1009: Saldos de cuentas por pagar')
            ->emptyStateDescription('Similar al formato 1008, pero enfocado en las cuentas por pagar.
                            Se reportan los saldos de las obligaciones pendientes al cierre del año, detallando el tipo de documento del acreedor,
                            número de identificación, concepto de la cuenta por pagar, y el saldo correspondiente.')
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1009Exporter::class)
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
            'index' => Pages\ListExogena1009s::route('/'),
            'create' => Pages\CreateExogena1009::route('/create'),
            'edit' => Pages\EditExogena1009::route('/{record}/edit'),
        ];
    }
}
