<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1008Resource\Pages;
use App\Models\Exogena1008;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\Informe1008Exporter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class Exogena1008Resource extends Resource
{
    protected static ?string $model = Exogena1008::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1008 - Saldos de Cuentas por Cobrar';
    protected static ?string $modelLabel = '1008 - Saldos de Cuentas por Cobrar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Formato 1008: Saldos de cuentas por cobrar')
            ->paginated(false)
            ->description('Este formato está destinado a reportar los saldos de las cuentas por cobrar al cierre del año gravable.
                                        Se debe informar el tipo de documento del deudor, número de identificación, concepto de la cuenta por cobrar,
                                        y el saldo correspondiente.')
            ->striped()
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-fire')
            ->emptyStateHeading('')
            ->columns([])
            ->headerActions([
                Action::make('Actualizar Vista')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->form([
                        DatePicker::make('fecha_corte')
                            ->required()
                            ->maxDate(now()->format('Y-m'))
                            ->placeholder('Seleccione el año y mes')
                            ->displayFormat('F Y')
                    ])
                    ->modalWidth('sm')
                    ->modalHeading('Actualizar Consulta')
                    ->modalDescription('¿Fecha de Corte de la informacion?')
                    ->modalSubmitActionLabel('Generar')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->action(function (array $data): void {
                        $fechaCorte = Carbon::parse($data['fecha_corte']);
                        DB::statement("SELECT public.refresh_vista_exogena_1008(?, ?)", [
                            $fechaCorte->format('Y'),
                            $fechaCorte->format('m'),
                        ]);
                        session()->put('vista_actualizada', true);
                        Notification::make()
                            ->title('La consulta de informacion se ha actualizado correctamente.')
                            ->success()
                            ->send();
                    })
                    ->label('Consulta Informacion'),
                ExportAction::make()
                    ->modalWidth('sm')
                    ->modalHeading('Que columnas del informe desea exportar?')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->modalSubmitActionLabel('Exportar')
                    ->color('secondary')
                    ->visible(fn() => session()->get('vista_actualizada', false))
                    ->exporter(Informe1008Exporter::class)
                    ->columnMapping(false)
                    ->fileName(fn(Export $export): string => "Informe_1008_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
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
            'index' => Pages\ListExogena1008s::route('/'),
            'create' => Pages\CreateExogena1008::route('/create'),
            'edit' => Pages\EditExogena1008::route('/{record}/edit'),
        ];
    }
}
