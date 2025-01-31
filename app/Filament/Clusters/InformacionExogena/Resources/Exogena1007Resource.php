<?php

namespace App\Filament\Clusters\InformacionExogena\Resources;

use App\Filament\Clusters\InformacionExogena;
use App\Filament\Clusters\InformacionExogena\Resources\Exogena1007Resource\Pages;
use App\Models\Exogena1007;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\CentralRiesgoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;


class Exogena1007Resource extends Resource
{
    protected static ?string $model = Exogena1007::class;
    protected static ?string $cluster = InformacionExogena::class;
    protected static ?string $navigationLabel = '1007 - Ingresos Recibidos';
    protected static ?string $modelLabel = '1007 - Ingresos Recibidos';




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
            ->heading('Informe 1007-Ingresos Recibidos')
            ->description('En este módulo podrás generar los diferentes informes de la información exógena requerida por la DIAN')
            ->paginated(false)
            ->striped()
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(CentralRiesgoExporter::class)
                    ->form([
                        DatePicker::make('fecha_corte')
                            ->label('Fecha de Corte')
                            ->required(),
                        Select::make('Tipo_Informe')
                            ->label('Tipo de Informe')
                            ->required()
                            ->options([
                                '1' => 'Informe Central Datacredito'
                            ])
                    ])
                    ->modifyQueryUsing(function (Builder $query, array $data) {
                        $query->where('fecha_corte', $data['fecha_corte']);

                        /* ->limit(10); */
                        //dd($query, $data);
                        //dd(DB::table('asociados')->get());
                        //dd($query->where('cliente', '19240474')->get());
                        //$query->where('cliente', '19240474')->get();
                        //$query->join('asociados', DB::raw('cartera_encabezados_corte.cliente'), '=', DB::raw('asociados.codigo_interno_pag::bigint'))
                        //    ->select('asociados.codigo_interno_pag', 'cartera_encabezados_corte.id');
                        //DB::table('asociados')->get();
                        //$query->from('asociados')->where('codigo_interno_pag', '"19307511"');
                    })
                    ->columnMapping(false)
                    ->label('Generar Informe')
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Informe 1007-Ingresos Recibidos')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla el formato 1007.');
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
            'index' => Pages\ListExogena1007::route('/'),
            'create' => Pages\CreateExogena1007::route('/create'),
            'edit' => Pages\EditExogena1007::route('/{record}/edit'),
        ];
    }
}
