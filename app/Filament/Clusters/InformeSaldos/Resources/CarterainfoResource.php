<?php

namespace App\Filament\Clusters\InformeSaldos\Resources;

use App\Filament\Clusters\InformeSaldos;
use App\Filament\Clusters\InformeSaldos\Resources\CarterainfoResource\Pages;
use App\Models\Carterainfo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\CarterainfoExporter;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class CarterainfoResource extends Resource
{
    protected static ?string $model = Carterainfo::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Saldos de Cartera';
    protected static ?string $modelLabel = 'Saldos de Cartera';
    protected static ?string $cluster = InformeSaldos::class;

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
                        DB::statement("SELECT public.crear_vista_carterainfo();");
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
                    ->exporter(CarterainfoExporter::class)
                    ->fileName(fn (Export $export): string => "Saldos_Cartera_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarterainfos::route('/'),
            'create' => Pages\CreateCarterainfo::route('/create'),
            'edit' => Pages\EditCarterainfo::route('/{record}/edit'),
        ];
    }
}
