<?php

namespace App\Filament\Resources;


use App\Filament\Exports\SuperSolidariaExporter;
use App\Filament\Resources\SuperSolidariaResource\Pages;
use App\Filament\Resources\SuperSolidariaResource\RelationManagers;
use App\Models\SuperSolidaria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ExportAction;

class SuperSolidariaResource extends Resource
{
    protected static ?string $model = SuperSolidaria::class;
    protected static?string $navigationIcon = 'heroicon-o-cursor-arrow-rays';
    protected static?string $navigationLabel = 'Informes  SuperSolidaria';
    protected static?string $modelLabel = 'Informes SuperSolidaria';
    protected static ?string $navigationGroup = 'Informes de Cumplimiento';
    protected static ?int       $navigationSort = 9;

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
            ->heading('Informes Superintendencia Solidaria')
            ->description('En este módulo podrás generar de forma sencilla todos los reportes a trasmitir a la SuperSolidaria.')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('primary')
                    ->exporter(SuperSolidariaExporter::class)
                    ->form([

                        DatePicker::make('fecha_corte')
                            ->label('Fecha Corte')
                            ->required(),
                        Select::make('Tipo_Informe')
                            ->label('Tipo de Informe')
                            ->required()
                            ->options([
                                '1' => 'Anexo Cartera',
                                '2' => 'Asociados, empleados y deudores por ventas de bienes',
                                '3' => 'Catálogo de cuentas',
                                '4' => 'Crédito de Bancos y otros',
                                '5' => 'Erogaciones a órganos de control',
                                '6' => 'Evaluación Riesgo de Liquidez',
                                '7' => 'Fondo de liquidez',
                                '8' => 'Información estadística',
                                '9' => 'Información cuentas por pagar',
                                '10' => 'Individual de aportes',
                                '11' => 'Individual de cartera de crédito',
                                '12' => 'Individual de captaciones',
                                '13' => 'Informe órganos de dirección y control',
                                '14' => 'Red de Oficinas y corresponsales bancarios',
                                '15' => 'Relación de Inversiones',
                                '16' => 'Relación de Propiedad, planta y equipo',
                                '17' => 'Revelaciones Taxonómicas'

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
            'index' => Pages\ListSuperSolidarias::route('/'),
            'create' => Pages\CreateSuperSolidaria::route('/create'),
            'view' => Pages\ViewSuperSolidaria::route('/{record}'),
            'edit' => Pages\EditSuperSolidaria::route('/{record}/edit'),
        ];
    }
}
