<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\RelationManagers;
use App\Models\Exogena1008;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\Informe1008Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class Exogena1008Resource extends Resource
{
    protected static ?string $model = Exogena1008::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1008 - Saldos de Cuentas por Cobrar';
    protected static ?string $modelLabel = '1008 - Saldos de Cuentas por Cobrar';

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
            ->heading('Formato 1008: Saldos de cuentas por cobrar')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1008: Saldos de cuentas por cobrar')
            ->emptyStateDescription('Este formato está destinado a reportar los saldos de las cuentas por cobrar al cierre del año gravable.
            Se debe informar el tipo de documento del deudor, número de identificación, concepto de la cuenta por cobrar, y el saldo correspondiente.')

            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1008Exporter::class)
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
            'index' => Pages\ListExogena1008s::route('/'),
            'create' => Pages\CreateExogena1008::route('/create'),
            'edit' => Pages\EditExogena1008::route('/{record}/edit'),
        ];
    }
}
