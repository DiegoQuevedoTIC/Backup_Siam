<?php

namespace App\Filament\Resources\CierreMensualResource\Pages;

use App\Filament\Resources\CierreMensualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListCierreMensuals extends ListRecords
{
    protected static string $resource = CierreMensualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(function () {
                    return DB::table('cierre_mensuales')
                        ->where('estado', 'procesando')
                        ->orderBy('created_at', 'DESC')
                        ->limit(1)
                        ->doesntExist();
                }),
        ];
    }
}
