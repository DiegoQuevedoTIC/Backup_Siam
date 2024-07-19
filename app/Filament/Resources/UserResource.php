<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string    $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string    $navigationLabel = 'Creacion de Usuarios';
    protected static ?string    $navigationGroup = 'Roles y Permisos';
    protected static ?string    $modelLabel = 'Roles/Usuario';

    public static function form(Form $form): Form
    {
        return $form
        
            ->schema([
                TextInput::make('name')
                ->required(),
                TextInput::make('email')
                ->required()
                ->email(),
                TextInput::make('email_verified_at')
                ->hiddenOn('create')               ,
                TextInput::make('password')
                ->required()
                ->hiddenOn('edit')
                ->password(),
                Select::make('roles')->multiple(true)->relationship('roles','name') ,
            ]);

            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('email_verified_at'),
              
            ])
            ->filters([
                Tables\Filters\Filter::make('Verificado')
                ->query(fn (Builder $query): Builder => $query->whereNotNull('email_verified_at')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Verificado')
                ->icon('heroicon-m-check-badge')
                ->action(function (User $user) {
                    $user->email_verified_at=date('Y-m-d H:i:s');
                    $user->save();
                }),
                Tables\Actions\Action::make('Inactivar')
                ->icon('heroicon-m-x-circle')
                ->color('warning')
                ->action(function (User $user) {
                    $user->email_verified_at=null;
                    $user->save();
                })
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}
