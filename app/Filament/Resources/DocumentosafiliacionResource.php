<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\ModulosDigitalizacion;
use App\Filament\Resources\DocumentosafiliacionResource\Pages;
use App\Filament\Resources\DocumentosafiliacionResource\RelationManagers;
use App\Models\Documentosafiliacion;
use App\Models\Documentoclase;
use App\Models\Tercero;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;



class DocumentosafiliacionResource extends Resource
{
    protected static ?string $model = Documentosafiliacion::class;
    protected static ?string    $cluster = ModulosDigitalizacion::class;
    protected static ?string    $navigationIcon = 'heroicon-o-identification';
    protected static ?string    $navigationLabel = 'Digitalizacion Afiliaciones';
    protected static ?string    $navigationGroup = 'Gestion Documental';
    protected static ?string    $modelLabel = 'Documento Afiliacion';
    protected static ?string    $pluralModelLabel = 'Digitalizacion Afiliaciones';
    protected static ?string    $slug = 'Par/Tab/Afiliaciones';
    protected static ?int       $navigationSort = 4;





    public static function form(Form $form): Form
    {
        return $form
            ->columns(5)
            ->schema([
                Select::make('documentoclase_id')
                        ->label('Clase de Documento')
                        ->columnSpan(3)
                        ->disabled(fn ($record) => optional($record)->exists ?? false)
                        ->options(Documentoclase::query()->pluck('nombre', 'id'))
                        ->live(),
                Select::make('tercero_id')
                        ->label('No Id Tercero Vinculado')
                        ->columnSpan(2)
                        ->disabled(fn ($record) => optional($record)->exists ?? false)
                        ->options(Tercero::query()->pluck('tercero_id', 'id'))
                        ->searchable()
                        ->unique(ignoreRecord: true)
                        ->columnSpan(4)
                        ->prefix('Id'),
                FileUpload::make('ruta_imagen')
                        ->label('Afiliacion')
                        ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('Afiliacion-'),
                                )
                        ->columnSpan(8)
                        ->openable()
                        ->deletable(false)
                        ->downloadable()
                        ->previewable(true)
                        ->disk('spaces')
                        ->directory('afiliaciones')
                        ->visibility('public'),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tercero.tercero_id')
                        ->label('Documento')
                        ->searchable(),
                TextColumn::make('tercero.nombres')
                        ->label('Tipo Documento')
                        ->searchable(),
                TextColumn::make('tercero.primer_apellido')
                        ->label('Tipo segundo_apellido')
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
            'index' => Pages\ListDocumentosafiliacions::route('/'),
            'create' => Pages\CreateDocumentosafiliacion::route('/create'),
            'edit' => Pages\EditDocumentosafiliacion::route('/{record}/edit'),
        ];
    }
}
