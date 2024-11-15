<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ComunicacionExterna;
use App\Filament\Resources\MensajeTextoResource\Pages;
use App\Filament\Resources\MensajeTextoResource\RelationManagers;
use App\Models\MensajeTexto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MensajeTextoResource extends Resource
{
    protected static ?string $model = MensajeTexto::class;
    protected static ?string $cluster = ComunicacionExterna::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Mensajes de texto (SMS)';
    protected static ?string $modelLabel = 'Mensaje de texto (SMS)';

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
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
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
            'index' => Pages\ListMensajeTextos::route('/'),
            'create' => Pages\CreateMensajeTexto::route('/create'),
            'view' => Pages\ViewMensajeTexto::route('/{record}'),
            'edit' => Pages\EditMensajeTexto::route('/{record}/edit'),
        ];
    }
}
