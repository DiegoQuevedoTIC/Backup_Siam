<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PucResource\Pages;
use App\Filament\Resources\PucResource\RelationManagers;
use App\Models\Puc;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Actions\Action;

class PucResource extends Resource
{
    protected static ?string $model = Puc::class;

    protected static ?string    $navigationIcon = 'heroicon-o-swatch';
    protected static ?string    $navigationLabel = 'Plan Unico de Cuentas';
    protected static ?string    $navigationGroup = 'Contabilidad';
    protected static ?string    $navigationParentItem = 'Parametros Contabilidad';
    protected static ?string    $modelLabel = 'PUC - Cuenta';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                TextInput::make('puc')
                    ->maxLength(10)
                    ->required()
                    ->label('Cuenta PUC'),
                TextInput::make('grupo')
                    ->maxLength(1)
                    ->required()
                    ->label('Grupo Cuenta'),
                TextInput::make('descripcion')
                    ->maxLength(255)
                    ->required()
                    ->label('Descripcion Cuenta'),
                TextInput::make('nivel')
                    ->maxLength(1)
                    ->required()
                    ->label('Nivel Cuenta'),
                Select::make('puc_padre')
                    ->label('PUC Padre')
                    ->searchable()
                    ->native(false)
                    ->getSearchResultsUsing(fn (string $search): array => Puc::where('puc_padre', '=', "{$search}")->orWhere('descripcion', 'like', "%{$search}%")->limit(50)->pluck('descripcion', 'id')->toArray())
                    ->getOptionLabelUsing(fn ($value): ?string => Puc::find($value)?->puc),
                Select::make('naturaleza')
                    ->required()
                    ->label('Naturaleza de la cuenta')
                    ->options([
                        'D' => 'Debito',
                        'C' => 'Credito ',
                    ]),
                Toggle::make('mayor_rep')
                    ->required()
                    ->label('Cuenta mayor?'),
                Toggle::make('movimiento')
                    ->required()
                    ->label('Cuenta permite movimiento?'),
                Toggle::make('subcentro')
                    ->required()
                    ->label('Cuenta se maneja por subcentro?'),
                Toggle::make('bancaria')
                    ->required()
                    ->label('Es cuenta bancaria?'),
                Toggle::make('tercero')
                    ->required()
                    ->label('Cuenta requiere adminisracion por Terceros?'),
                Toggle::make('base_gravable')
                    ->required()
                    ->label('Cuenta solicita base gravable?'),
                Toggle::make('mueve_modulo')
                    ->required()
                    ->label('Es cuenta de conciliacion?'),
                Toggle::make('codigo_dian')
                    ->required()
                    ->label('Es cuenta que reporta a la DIAN?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('puc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
            ])
            ->defaultSort('puc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListPucs::route('/'),
            'create' => Pages\CreatePuc::route('/create'),
            'edit' => Pages\EditPuc::route('/{record}/edit'),
        ];
    }
}
