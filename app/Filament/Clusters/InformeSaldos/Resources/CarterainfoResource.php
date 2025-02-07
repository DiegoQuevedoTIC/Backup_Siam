<?php

namespace App\Filament\Clusters\InformeSaldos\Resources;

use App\Filament\Clusters\InformeSaldos;
use App\Filament\Clusters\InformeSaldos\Resources\CarterainfoResource\Pages;
use App\Models\Carterainfo;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\CarterainfoExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Filament\Forms\Components\Select;

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
            ->heading('Saldo Cartera de Credito ')
            ->description('Saldo de capital de Cartera a la Fecha.')
            ->paginated(false)
            ->striped()
            ->defaultPaginationPageOption(5)
            ->columns([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->color('secondary')
                    ->exporter(CarterainfoExporter::class)
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
            ->emptyStateHeading('Saldos de Cartera')
            ->emptyStateDescription('Saldo de capital de Cartera a la Fecha.');
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
