<?php

namespace App\Filament\Clusters\InformeSaldosCdat\Resources;

use App\Filament\Clusters\InformeSaldosCdat;
use App\Filament\Clusters\InformeSaldosCdat\Resources\CdatinfoResource\Pages;
use App\Filament\Clusters\InformeSaldosCdat\Resources\CdatinfoResource\RelationManagers;
use App\Models\Cdatinfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\CdatinfoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Carbon\Carbon;
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;

class CdatinfoResource extends Resource
{
    protected static ?string $model = Cdatinfo::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = InformeSaldosCdat::class;

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
            ->heading('Informes Cdats')
            ->description('Consulte la sabana de titulos CDATS.')
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
                        DB::statement("SELECT public.crear_vista_cdatinfo();");
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
                    ->exporter(CdatinfoExporter::class)
                    ->fileName(fn (Export $export): string => "Saldos_CDATs_-{$export->getKey()}.csv")
                    ->label('Descargar Informe')
                    ->after(function () {
                        session()->forget('vista_actualizada');
                    }),
            ]);
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCdatinfos::route('/'),
            'create' => Pages\CreateCdatinfo::route('/create'),
            'edit' => Pages\EditCdatinfo::route('/{record}/edit'),
        ];
    }
}
