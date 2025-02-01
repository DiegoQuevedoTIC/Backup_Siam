<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1001Resource\Pages;
use App\Models\Exogena1001;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\Informe1001Exporter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class Exogena1001Resource extends Resource
{
    protected static ?string $model = Exogena1001::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1001 - Informacion de Terceros';
    protected static ?string $modelLabel = '1001 - Informacion de Terceros';

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
            ->heading('Formato 1001: Pagos o abonos en cuenta y retenciones practicadas')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Formato 1001: Pagos o abonos en cuenta y retenciones practicadas')
            ->emptyStateDescription('Este formato se utiliza para reportar los pagos o abonos en cuenta efectuados a terceros,
                         así como las retenciones en la fuente practicadas a título de renta, IVA y timbre.
                         Incluye detalles como el tipo de documento del beneficiario, número de identificación,
                         concepto del pago o abono, valor del pago o abono, y valor de la retención practicada.')
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->form([
                        DatePicker::make('fecha_corte')
                        ->label('Fecha de Corte')
                        ->required()
                        ->maxDate(now()->format('Y-m')) // Fecha máxima (el mes actual)
                        ->placeholder('Seleccione el año y mes'),
                    ])
                    ->exporter(Informe1001Exporter::class)
                    ->columnMapping(false)
                    ->fileName(fn (Export $export): string => "Informe_1001_-{$export->getKey()}.csv")

                    ->label('Generar Informe'),
            ]);
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
            'index' => Pages\ListExogena1001::route('/'),
            'create' => Pages\CreateExogena1001::route('/create'),
            'edit' => Pages\EditExogena1001::route('/{record}/edit'),
        ];
    }
}
