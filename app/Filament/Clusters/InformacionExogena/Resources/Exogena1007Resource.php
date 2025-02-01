<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource\Pages;
use App\Models\Exogena1007;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\Informe1007Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;


class Exogena1007Resource extends Resource
{
    protected static ?string $model = Exogena1007::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1007 - Ingresos Recibidos';
    protected static ?string $modelLabel = '1007 - Ingresos Recibidos';




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
            ->heading('Formato 1007: Ingresos recibidos')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1007: Ingresos recibidos')
            ->emptyStateDescription('En este formato se deben reportar los ingresos recibidos durante el año gravable.
            Incluye información sobre el tipo de documento del pagador, número de identificación, concepto del ingreso, y valor del ingreso recibido.')

            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1007Exporter::class)
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
            'index' => Pages\ListExogena1007::route('/'),
            'create' => Pages\CreateExogena1007::route('/create'),
            'edit' => Pages\EditExogena1007::route('/{record}/edit'),
        ];
    }
}
