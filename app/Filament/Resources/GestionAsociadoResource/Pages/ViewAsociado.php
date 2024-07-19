<?php

namespace App\Filament\Resources\GestionAsociadoResource\Pages;

use App\Filament\Resources\GestionAsociadoResource;
use App\Models\Asociado;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\DB;

class ViewAsociado extends ViewRecord
{
    protected static string $resource = GestionAsociadoResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('codigo_interno_pag')
                    ->label('Nro Identificacion asociado')
                    ->columns(2),
                TextEntry::make('tercero.nombres')
                    ->label('Nombre de Asociado')
                    ->placeholder('Nombre de Asociado')
                    ->columns(2),
                TextEntry::make('EstadoCliente.nombre')
                    ->label('Estado')
                    ->placeholder('Estado')
                    ->columns(2),
                TextEntry::make('pagaduria.nombre')
                    ->label('Pagaduria')
                    ->placeholder('Pagaduria')
                    ->columns(2),
            ]);
    }
}
