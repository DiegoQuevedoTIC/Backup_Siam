<?php

namespace App\Filament\Resources\ReaperturaCierreResource\Pages;

use App\Filament\Resources\ReaperturaCierreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateReaperturaCierre extends CreateRecord
{
    protected static string $resource = ReaperturaCierreResource::class;

    protected function afterCreate(): void
    {
        /* DB::statement('CALL reaperturar_cierre(?, ?);', [
            $this->getRecord()->amo,
            $this->getRecord()->mes,
        ]); */
    }
}
