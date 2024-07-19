<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentosotroResource\Pages;
use App\Filament\Resources\DocumentosotroResource\RelationManagers;
use App\Models\Documentosotro;
use App\Models\Documentoclase;
use App\Models\Documentotipo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Illuminate\Support\Collection;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DocumentosotroResource extends Resource
{
    protected static ?string $model = Documentosotro::class;

    protected static ?string    $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?string    $navigationLabel = 'Actas, Informes y Otros';
    protected static ?string    $navigationGroup = 'Gestion Documental';
    protected static ?string    $modelLabel = 'Acta, Informe u Otros';
    protected static ?string    $pluralModelLabel = 'Actas, Informes y Otros';
    protected static ?string    $slug = 'Par/Tab/Varios';
    protected static ?int       $navigationSort = 5;

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
            DatePicker::make('fecha_documento')
                ->markAsRequired(false)
                ->required()
                ->columnSpan(2)
                ->label('Fecha de Nacimiento'),
            FileUpload::make('ruta_imagen')
                ->label('Adjunte el soporte...')
                ->getUploadedFileNameForStorageUsing(
                    fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                ->prepend('Afiliacion-'),
                                )
                ->columnSpan(8)
                ->openable()
                ->deletable(false)
                ->downloadable()
                ->previewable(true)
                ->disk('public')
                ->directory('varios')
                ->visibility('public'),
            Textarea::make('descripcion')
                ->maxLength(65535)
                ->markAsRequired(false)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('documentotipo.nombre')
                        ->label('Tipo Documento'),
            TextColumn::make('fecha_documento')
                        ->label('Fecha Documento')
                        ->searchable(),
            TextColumn::make('descripcion')
                        ->label('Detalle Documento')
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
            'index' => Pages\ListDocumentosotros::route('/'),
            'create' => Pages\CreateDocumentosotro::route('/create'),
            'edit' => Pages\EditDocumentosotro::route('/{record}/edit'),
        ];
    }
}
