<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1012Resource\Pages;
use App\Models\Exogena1012;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\Informe1012Exporter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class Exogena1012Resource extends Resource
{
    protected static ?string $model = Exogena1012::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1012 - Aportes y Inversiones';
    protected static ?string $modelLabel = '1012 - Aportes y Inversiones';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Formato 1012: Información de declaraciones tributarias, acciones, inversiones en bonos, cuentas de ahorro y corrientes')
            ->description('Este formato abarca información sobre declaraciones tributarias, así como inversiones en acciones,
                            bonos, y saldos en cuentas de ahorro y corrientes.
                            Se debe detallar el tipo de inversión o cuenta, entidad financiera, número de cuenta o identificación de la inversión,
                            y saldos o valores correspondientes.')
            ->paginated(false)
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
                        DB::statement("SELECT public.refresh_vista_exogena_1012(?, ?)", [
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
                    ->color('secondary')
                    ->modalWidth('sm')
                    ->modalHeading('Que columnas del informe desea exportar?')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->modalSubmitActionLabel('Exportar')
                    ->visible(fn() => session()->get('vista_actualizada', false))
                    ->exporter(Informe1012Exporter::class)
                    ->columnMapping(false)
                    ->fileName(fn(Export $export): string => "Informe_1012_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
