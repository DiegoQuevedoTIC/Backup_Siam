<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoResource\Pages;
use App\Filament\Resources\DocumentoResource\RelationManagers;
use App\Models\Documento;
use App\Models\Documentoclase;
use App\Models\Documentotipo;
use App\Models\Solicitud;
use App\Models\Tercero;
use App\Models\Comprobante;
use Filament\Forms\ComponentContainer;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DocumentoResource extends Resource
{
    protected static ?string $model = Documento::class;

    protected static ?string    $navigationIcon = 'heroicon-m-rocket-launch';
    protected static ?string    $navigationLabel = 'Digitalizacion Pagares';
    protected static ?string    $navigationGroup = 'Gestion Documental';
    protected static ?string    $modelLabel = 'Documento Pagare';
    protected static ?string    $pluralModelLabel = 'Digitalizacion de Pagares';
    protected static ?string    $slug = 'Par/Tab/Digitalizar';
    protected static ?int       $navigationSort = 2;

    public static function form(Form $form): Form
    {
            return $form
            ->columns(6)
            ->schema([
            Select::make('documentoclase_id')
                ->label('Clase Documental')
                ->columnSpan(4)
                ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                ->options(Documentoclase::all()->pluck('nombre', 'id'))
                ->live(),
            Select::make('llave_de_consulta_id')
                ->columnSpan(2)
                ->label('No Pagare')
                ->searchable()
                ->disabled(fn ($record) => optional($record)->exists ?? false) // Verificar si $record existe antes de acceder a ->exists
                ->unique(ignoreRecord: true)
                ->options(function (Get $get): Collection {
                    if ($get('documentoclase_id') == 1) {
                        return Collection::make(Solicitud::query()->pluck('solicitud', 'id')->toArray());
                    } elseif ($get('documentoclase_id') == 2) {
                        return Collection::make(Comprobante::query()->pluck('n_documento', 'id')->toArray());
                    } else {
                        return Collection::make([]);
                    }
                }),
            FileUpload::make('ruta_imagen')
                ->label('Pagare y Carta de Instucciones')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Pagare-PG'),
                         )
                ->columnSpan(6)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('pagares')
                ->visibility('public'),
            FileUpload::make('ruta_imagen_1')
                ->label('Comprobante Contabilidad')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Comprobante_Contable-CE'),
                         )
                ->columnSpan(6)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('contabilidad')
                ->visibility('public'),
            FileUpload::make('ruta_imagen_2')
                ->label('Formulario de Solicitud')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Solicitud-SC'),
                         )
                ->columnSpan(6)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('formularios_solicitud')
                ->visibility('public'),
            FileUpload::make('ruta_imagen_3')
                ->label('Seguro de Vida Deudores')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Seguro-SG'),
                         )
                ->columnSpan(6)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('Seguros')
                ->visibility('public'),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('documentoclase.sigla')
                        ->label('Sigla TD'),
                TextColumn::make('llave_de_consulta_id')
                        ->label('Pagare')
                        ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListDocumentos::route('/'),
            'create' => Pages\CreateDocumento::route('/create'),
            'edit' => Pages\EditDocumento::route('/{record}/edit'),
        ];
    }
}
