<?php

namespace App\Filament\Resources;



use App\Filament\Resources\CentralRiesgoResource\Pages;
use App\Models\CentralRiesgo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\CentralRiesgoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class CentralRiesgoResource extends Resource
{
    protected static ?string $model = CentralRiesgo::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Informes Centrales de Riesgo';
    protected static ?string $modelLabel = 'Informes Centrales de Riesgo';
    protected static ?string $navigationGroup = 'Informes de Cumplimiento';

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
            ->heading('Centrales de Riesgo')
            ->description('En este módulo podrás generar de forma sencilla los archivos de reporte para envio a las diferentes centrales de Riesgo.
                          Debe ser generado a la fecha de un cierre de cartera.')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                //
            ])
            ->emptyStateActions([])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Informe Centrales de Riesgo')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla los archivos de reporte para envio a las diferentes centrales de Riesgo.');
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
            'index' => Pages\ListCentralRiesgos::route('/'),
            'create' => Pages\CreateCentralRiesgo::route('/create'),
            'edit' => Pages\EditCentralRiesgo::route('/{record}/edit'),
        ];
    }
}
