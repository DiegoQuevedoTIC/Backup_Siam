<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1010Resource\Pages;
use App\Models\Exogena1010;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\Informe1010Exporter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class Exogena1010Resource extends Resource
{
    protected static ?string $model = Exogena1010::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1010 - Informe de Socios';
    protected static ?string $modelLabel = '1010 - Informe de Socios';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Formato 1010: Información de socios, accionistas, cooperados')
            ->description('Este formato se utiliza para reportar información sobre los socios, accionistas o cooperados de la entidad.
                     Incluye datos como el tipo y número de documento, nombres y apellidos o razón social, porcentaje de participación, y valor de los aportes o acciones.')
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
                        DB::statement("SELECT public.refresh_vista_exogena_1010(?, ?)", [
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
                    ->exporter(Informe1010Exporter::class)
                    ->columnMapping(false)
                    ->fileName(fn(Export $export): string => "Informe_1010_-{$export->getKey()}.csv")
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
            'index' => Pages\ListExogena1010s::route('/'),
            'create' => Pages\CreateExogena1010::route('/create'),
            'edit' => Pages\EditExogena1010::route('/{record}/edit'),
        ];
    }
}
