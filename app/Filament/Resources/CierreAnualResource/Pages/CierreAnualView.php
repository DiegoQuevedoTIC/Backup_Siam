<?php

namespace App\Filament\Resources\CierreAnualResource\Pages;

use App\Filament\Resources\CierreAnualResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CierreAnualView extends ViewRecord
{
    protected static string $resource = CierreAnualResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('ano_cierre')->label('Año'),
            DatePicker::make('fecha_cierre')->label('Fecha del cierre')->format('d/m/Y'),
            TableRepeater::make('detalles')
            ->relationship('detalles')
            ->schema([
               Select::make('pucs_id')->label('PUC'),
               TextInput::make('saldo_anterior')->label('Saldo Anterior'),
               TextInput::make('saldo_actual')->label('Saldo Actual'),
               TextInput::make('debito')->label('Débito'),
               TextInput::make('credito')->label('Crédito')
            ])
        ]);
    }
}
