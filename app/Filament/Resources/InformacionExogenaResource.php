<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\InformesCumplimiento;
use App\Filament\Resources\InformacionExogenaResource\Pages;
use App\Filament\Resources\InformacionExogenaResource\RelationManagers;
use App\Models\InformacionExogena;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use App\Filament\Exports\InformacionExogenaExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;

class InformacionExogenaResource extends Resource
{
    protected static ?string $model = InformacionExogena::class;
    protected static?string $cluster = InformesCumplimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static?string $navigationLabel = 'Información Exógena';
    protected static?string $modelLabel = 'Información Exógena';

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
            ->heading('Informacion Exogena')
            ->description('En este módulo podrás generar los diferentes informes de la información exógena requerida por la DIAN.')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(InformacionExogenaExporter::class)
                    ->form([
                        DatePicker::make('fecha_inicial')
                        ->label('Fecha Inicial')
                        ->required(),
                        DatePicker::make('fecha_final')
                            ->label('Fecha Final')
                            ->required(),
                        Select::make('Tipo_Informe')
                            ->label('Tipo de Informe')
                            ->required()
                            ->options([
                                '1001' => '1001: Información de Terceros',
                                '1007' => '1007: Ingresos Recibidos',
                                '1008' => '1008: Saldos de Cuentas por Cobrar',
                                '1009' => '1009: Saldos de Cuentas por Pagar',
                                '1010' => '1010: Información de Socios, Accionistas, Comuneros y/o Cooperados',
                                '1011' => '1011: Información de las Declaraciones Tributarias',
                                '1012' => '1012: Información de las Declaraciones Tributarias, Acciones y Aportes e Inversiones',
                                '1022' => '1022: Información de Retenciones en la Fuente por Rentas de Trabajo y Pensiones'
                            ])
                    ])
/*                     ->modifyQueryUsing(function (Builder $query, array $data) {
                        $query->where('fecha_corte', $data['fecha_corte']);
                    }) */
                    ->columnMapping(false)
                    ->label('Generar Informe')
            ])
            ->actions([])
            ->emptyStateActions([])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Informacion Exogena')
            ->emptyStateDescription('En este módulo podrás generar los diferentes informes de la información exógena requerida por la DIAN.');
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
            'index' => Pages\ListInformacionExogenas::route('/'),
            'create' => Pages\CreateInformacionExogena::route('/create'),
            'edit' => Pages\EditInformacionExogena::route('/{record}/edit'),
        ];
    }
}
