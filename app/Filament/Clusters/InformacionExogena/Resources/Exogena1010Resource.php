<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource\Pages;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource\RelationManagers;
use App\Models\Exogena1010;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\Informe1010Exporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class Exogena1010Resource extends Resource
{
    protected static ?string $model = Exogena1010::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1010 - Informe de Socios';
    protected static ?string $modelLabel = '1010 - Informe de Socios';

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
            ->heading('Formato 1010: Información de socios, accionistas, cooperados')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)

            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1010: Información de socios, accionistas, cooperados')
            ->emptyStateDescription('Este formato se utiliza para reportar información sobre los socios, accionistas o cooperados de la entidad.
                     Incluye datos como el tipo y número de documento, nombres y apellidos o razón social, porcentaje de participación, y valor de los aportes o acciones.')
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(Informe1010Exporter::class)
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
            'index' => Pages\ListExogena1010s::route('/'),
            'create' => Pages\CreateExogena1010::route('/create'),
            'edit' => Pages\EditExogena1010::route('/{record}/edit'),
        ];
    }
}
