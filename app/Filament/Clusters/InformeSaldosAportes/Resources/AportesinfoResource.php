<?php

namespace App\Filament\Clusters\InformeSaldosAportes\Resources;

use App\Filament\Clusters\InformeSaldosAportes;
use App\Filament\Clusters\InformeSaldosAportes\Resources\AportesinfoResource\Pages;
use App\Models\Aportesinfo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\AportesinfoExporter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ExportAction;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class AportesinfoResource extends Resource
{
    protected static ?string $model = Aportesinfo::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = InformeSaldosAportes::class;
    protected static ?string $navigationLabel = 'Saldos de Aportes y Ahorros';
    protected static ?string $modelLabel = 'Saldos de Aportes y Ahorros';

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
            ->heading('Informes Saldos de Aportes y Ahorros')
            ->description('Consulte la sabana de Saldos de Aportes y Ahorros a la fecha.')
            ->paginated()
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
                    ->modalWidth('sm')
                    ->modalHeading('Actualizar Consulta')
                    ->modalSubmitActionLabel('Generar')
                    ->modalIcon('heroicon-o-cloud-arrow-down')
                    ->action(function (array $data): void {
                        DB::statement("SELECT public.crear_vista_aportesinfo();");
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
                    ->exporter(AportesinfoExporter::class)
                    ->fileName(fn (Export $export): string => "Saldos_Aportes_Ahorros_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAportesinfos::route('/'),
            'create' => Pages\CreateAportesinfo::route('/create'),
            'edit' => Pages\EditAportesinfo::route('/{record}/edit'),
        ];
    }
}
