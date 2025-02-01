<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1022Resource\RelationManagers;
use App\Models\Exogena1022;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\Informe1022Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class Exogena1022Resource extends Resource
{
    protected static ?string $model = Exogena1022::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1022 - Retenciones de Trabajo y Pensiones';
    protected static ?string $modelLabel = '1022 - Retenciones de Trabajo y Pensiones';

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
            ->heading('Formato 1022: Información de ingresos recibidos para terceros')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1022: Información de ingresos recibidos para terceros')
            ->emptyStateDescription('Este formato se utiliza para reportar los ingresos que se han recibido en nombre de terceros.
                    Incluye información sobre el tipo y número de documento del tercero, concepto del ingreso, y valor del ingreso recibido.')
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1022Exporter::class)
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
            'index' => Pages\ListExogena1022s::route('/'),
            'create' => Pages\CreateExogena1022::route('/create'),
            'edit' => Pages\EditExogena1022::route('/{record}/edit'),
        ];
    }
}
