<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use App\Filament\Resources\CierreMensualResource\Widgets\ComprobanteMensualDetalleTable;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;


class ViewCierreMensual extends ViewRecord
{
    protected static string $resource = CierreMensualResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('mes_cierre')->label('Mes'),
            DatePicker::make('fecha_cierre')->label('Fecha del cierre')->format('d/m/Y'),
        ]);
    }
}
