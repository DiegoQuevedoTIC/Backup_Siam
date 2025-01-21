<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Tesoreria;
use App\Filament\Resources\PagoIndividualResource\Pages;
use App\Filament\Resources\PagoIndividualResource\RelationManagers;
use App\Livewire\CarteraTable;
use App\Models\PagoIndividual;
use App\Models\Tercero;
use App\Models\TipoDocumentoContable;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;

class PagoIndividualResource extends Resource
{
    protected static ?string $model = PagoIndividual::class;
    protected static ?string $cluster = Tesoreria::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationLabel = 'Pagos Individuales';
    protected static ?string $modelLabel = 'Pagos Individuales';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Action::make('create')
                    ->label('Gestionar')
                    ->url(route('filament.admin.tesoreria.resources.pago-individuals.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->emptyStateIcon('heroicon-o-bookmark')
            ->emptyStateHeading('Gestionar pagos individuales')
            ->emptyStateDescription('En este modulo podras gestionar de forma sencilla todos los pagos individuales de forma efectiva.');
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
            'index' => Pages\ListPagoIndividuals::route('/'),
            'create' => Pages\CreatePagoIndividual::route('/create'),
            'view' => Pages\ViewPagoIndividual::route('/{record}'),
            'edit' => Pages\EditPagoIndividual::route('/{record}/edit'),
        ];
    }
}
