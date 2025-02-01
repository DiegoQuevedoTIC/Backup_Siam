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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

use function Livewire\before;

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
            ->description('Este formato se utiliza para reportar los pagos o abonos en cuenta efectuados a terceros,
                 así como las retenciones en la fuente practicadas a título de renta, IVA y timbre.
                 Incluye detalles como el tipo de documento del beneficiario, número de identificación,
                 concepto del pago o abono, valor del pago o abono, y valor de la retención practicada.')
            ->paginated(false)
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-fire')
            ->emptyStateHeading('')
            ->columns([
            ])
            ->headerActions([
                // Acción para actualizar la vista
                Action::make('Actualizar Vista')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->form([
                        DatePicker::make('fecha_corte')
                            ->label('')
                            ->required()
                            ->maxDate(now()->format('Y-m'))
                            ->placeholder('Seleccione el año y mes')
                            ->displayFormat('F Y') // Para que se muestre mes y año (ej: "Marzo 2025"),
                    ])
                    ->modalWidth('sm')
                    ->modalHeading('Actualizar Consulta')
                    ->modalDescription('¿Fecha de Corte de la informacion?')
                    ->modalSubmitActionLabel('Generar')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->action(function (array $data): void {
                        $fechaCorte = Carbon::parse($data['fecha_corte']);
                        DB::statement("SELECT public.refresh_vista_exogena_1001(?, ?)", [
                            $fechaCorte->format('Y'),
                            $fechaCorte->format('m'),
                        ]);
                        // Almacenar en la sesión que la vista ya fue actualizada
                        session()->put('vista_actualizada', true);
                        // Opcional: Notificar al usuario
                        Notification::make()
                            ->title('La consulta de informacion se ha actualizado correctamente.')
                            ->success()
                            ->send();
                    })
                    ->label('Consulta Informacion'),

                // Acción para exportar el informe, visible solo si la vista fue actualizada
                ExportAction::make()
                    ->color('secondary')
                    ->modalWidth('sm')
                    ->modalHeading('Que columnas del informe desea exportar?')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->modalSubmitActionLabel('Exportar')
                    ->visible(fn () => session()->get('vista_actualizada', false))
                    ->exporter(Informe1001Exporter::class)
                    ->fileName(fn (Export $export): string => "Informe_1001_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    // Opcional: Después de exportar, limpiar el flag para obligar a actualizar de nuevo la vista
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
            ]);
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
