<?php

namespace App\Filament\Resources;


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
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class InformacionExogenaResource extends Resource
{
    protected static ?string $model = InformacionExogena::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static?string $navigationLabel = 'Información Exógena';
    protected static?string $modelLabel = 'Información Exógena';
    protected static ?string $navigationGroup = 'Informes de Cumplimiento';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Informacion Exogena')
            ->description('En este módulo podrás generar los diferentes informes de la información exógena requerida por la DIAN.')
            ->paginated(false)
            ->columns([])
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
