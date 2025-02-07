<?php

namespace App\Filament\Clusters\InformeSaldosAportes\Resources;

use App\Filament\Clusters\InformeSaldosAportes;
use App\Filament\Clusters\InformeSaldosAportes\Resources\AportesinfoResource\Pages;
use App\Filament\Clusters\InformeSaldosAportes\Resources\AportesinfoResource\RelationManagers;
use App\Models\Aportesinfo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\AportesinfoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms\Components\Select;

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
            ->heading('Saldo Aportes y Ahorros ')
            ->description('Saldo de Aportes y Ahorros.')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('secondary')
                    ->exporter(AportesinfoExporter::class)
                    ->form([
                        DatePicker::make('fecha_corte')
                            ->label('Fecha de Corte')
                            ->required(),
                        Select::make('Tipo_Informe')
                            ->label('Tipo de Informe')
                            ->required()
                            ->options([
                                '1' => 'Saldo de Cartera'
                            ])
                    ])
                    ->modifyQueryUsing(function (Builder $query, array $data) {
                        $query->where('fecha_corte', $data['fecha_corte']);
                    })
                    ->columnMapping(false)
                    ->label('Generar Informe')
            ])
            ->actions([])
            ->emptyStateActions([])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Saldos de Aportes y Ahorros')
            ->emptyStateDescription('Saldo de Aportes y Ahorros.');
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
