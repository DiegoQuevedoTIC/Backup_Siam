<?php

namespace App\Filament\Resources;

use App\Exports\CentralesExport;
use App\Filament\Clusters\InformesCumplimiento;
use App\Filament\Resources\CentralRiesgoResource\Pages;
use App\Filament\Resources\CentralRiesgoResource\RelationManagers;
use App\Models\CentralRiesgo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Exports\CentralRiesgoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ExportAction;

class CentralRiesgoResource extends Resource
{
    protected static ?string $model = CentralRiesgo::class;
    protected static ?string $cluster = InformesCumplimiento::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Informes Centrales de Riesgo';
    protected static ?string $modelLabel = 'Informes Centrales de Riesgo';

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
            ->columns([
                //
            ])
            ->filters([
                //
            ])

            ->headerActions([
                ExportAction::make()
                    ->exporter(CentralRiesgo::class)
                    ->form([
                        DatePicker::make('Fecha_Corte')
                            ->label('Fecha de Corte')
                            ->required(),
                        Select::make('Tipo_Informe')
                            ->label('Tipo de Informe')
                            ->options([
                                '1' => 'Datacredito',
                                '2' => 'Cifin',
                                '3' => 'Procredito'
                            ])
                            ->required()
                    ])
                    ->modifyQueryUsing(function (Builder $query, array $data) {
                        $query->where('fecha_corte', $data['Fecha_Corte']);
                        $query->where('tipo_informe', $data['Tipo_Informe']);
                    })
                    ->columnMapping(false)
                    ->label('Generar Informe')
            ])
            ->actions([])
            ->emptyStateActions([])

            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Informe Centrales de Riesgo')
            ->emptyStateDescription('En este módulo podrás generar de forma sencilla los archivos de reporte para envio a las diferentes centrales de Riesgo.')

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCentralRiesgos::route('/'),
            'create' => Pages\CreateCentralRiesgo::route('/create'),
            'edit' => Pages\EditCentralRiesgo::route('/{record}/edit'),
        ];
    }
}
