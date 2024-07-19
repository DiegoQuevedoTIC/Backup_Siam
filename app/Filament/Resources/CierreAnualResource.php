<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CierreAnualResource\Pages;
use App\Filament\Resources\CierreAnualResource\RelationManagers;
use App\Models\CierreAnual;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CierreAnualResource extends Resource
{
    protected static ?string $model = CierreAnual::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string    $navigationLabel = 'Cierre Anual';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Procesos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                DatePicker::make('fecha_cierre')
                ->label('Fecha Cierre')
                ->displayFormat('d/m/Y')
                ->native(false),
                TextInput::make('ano_cierre')
                ->label('Año Cierre')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('fecha_cierre')->label('Fecha Cierre')->date('d/m/Y'),
                TextColumn::make('ano_cierre')->label('Año cierre'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Ver'),
            ])
            ->bulkActions([
                /*Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])*/
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
            'index' => Pages\ListCierreAnuals::route('/'),
            'create' => Pages\CreateCierreAnual::route('/create'),
            'view'   => Pages\CierreAnualView::route('/{record}/view')
            //'edit' => Pages\EditCierreAnual::route('/{record}/edit'),
        ];
    }
}
