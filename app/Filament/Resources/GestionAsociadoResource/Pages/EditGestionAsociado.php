<?php

namespace App\Filament\Resources\GestionAsociadoResource\Pages;

use App\Filament\Resources\GestionAsociadoResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditGestionAsociado extends EditRecord
{
    protected static string $resource = GestionAsociadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Section::make('Estado de cuenta Asociado')
                    ->columns([
                        'sm' => 3,
                        'xl' => 6,
                        '2xl' => 8,
                    ])
                    ->headerActions([])
                    ->schema([
                        TextInput::make('codigo_interno_pag')
                            ->label('Nro Identificacion asociado')
                            ->disabled(function (Set $set) {
                                $set('codigo_interno_pag', $this->getRecord()->codigo_interno_pag);
                                return true;
                            })
                            ->columnSpan([
                                'sm' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ]),
                        TextInput::make('tercero')
                            ->label('Nombre de Asociado')
                            ->placeholder('Nombre de Asociado')
                            ->columnSpan([
                                'sm' => 3,
                                'xl' => 4,
                                '2xl' => 5,
                            ])->disabled(function (Set $set) {
                                $nombre = $this->getRecord()->tercero->nombres . " " . $this->getRecord()->tercero->primer_apellido . " " . $this->getRecord()->tercero->segundo_apellido;
                                $set('tercero', $nombre);
                                return true;
                            }),
                        TextInput::make('estado')
                            ->label('Estado')
                            ->placeholder('Estado')
                            ->columnSpan([
                                'sm' => 1,
                                'xl' => 2,
                                '2xl' => 3,
                            ])->disabled(function (Set $set) {
                                $set('estado', $this->getRecord()->EstadoCliente->nombre);
                                return true;
                            }),
                        TextInput::make('pagaduria')
                            ->label('Pagaduria')
                            ->placeholder('Pagaduria')
                            ->columnSpan([
                                'sm' => 3,
                                'xl' => 4,
                                '2xl' => 5,
                            ])->disabled(function (Set $set) {
                                $set('pagaduria', $this->getRecord()->pagaduria->nombre);
                                return true;
                            }),
                    ]),
            ]);
    }
}
