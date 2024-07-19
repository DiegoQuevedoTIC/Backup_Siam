<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentoscontableResource\Pages;
use App\Filament\Resources\DocumentoscontableResource\RelationManagers;
use App\Models\Documentoscontable;
use App\Models\Documentoclase;
use App\Models\Documentotipo;
use App\Models\Comprobante;
use App\Models\Solicitud;
use Filament\Forms\ComponentContainer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;



class DocumentoscontableResource extends Resource
{
    protected static ?string $model = Documentoscontable::class;

    protected static ?string    $navigationIcon = 'heroicon-o-paper-clip';
    protected static ?string    $navigationLabel = 'Digitalizacion Doc. Contables';
    protected static ?string    $navigationGroup = 'Gestion Documental';
    protected static ?string    $modelLabel = 'Documento Contable';
    protected static ?string    $pluralModelLabel = 'Digitalizacion Doc. Contables';
    protected static ?string    $slug = 'Par/Tab/Contables';
    protected static ?int       $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(8)
            ->schema([
            Select::make('documentoclase_id')
                ->label('Clase de Documento')
                ->columnSpan(3)
                ->disabled(fn ($record) => optional($record)->exists ?? false)
                ->options(Documentoclase::query()->pluck('nombre', 'id'))
                ->live(),
            Select::make('documentotipo_id')
                ->label('Tipo Documento')
                ->columnSpan(3)
                ->disabled(fn ($record) => optional($record)->exists ?? false)
                ->options(fn (Get $get): Collection => Documentotipo::query()
                ->where('documentoclase_id', $get('documentoclase_id'))
                ->pluck('nombre', 'id'))
                ->live(),
            TextInput::make('llave_de_consulta_id')
                ->columnSpan(2)
                ->label('No Comprobante')
                ->disabled(fn ($record) => optional($record)->exists ?? false)
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(7),
            FileUpload::make('ruta_imagen')
                ->label('Documento Contable')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Documento Contable'),
                         )
                ->columnSpan(8)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('registro_contable')
                ->visibility('public'),
            FileUpload::make('ruta_imagen_1')
                ->label('Soportes del Registro')
                ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                    ->prepend('Soporte'),
                         )
                ->columnSpan(8)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('registro_contable')
                ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('documentotipo.nombre')
                        ->label('Tipo Documento'),
                TextColumn::make('llave_de_consulta_id')
                        ->label('No de Documento')
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
            'index' => Pages\ListDocumentoscontables::route('/'),
            'create' => Pages\CreateDocumentoscontable::route('/create'),
            'edit' => Pages\EditDocumentoscontable::route('/{record}/edit'),
        ];
    }
}
