<?php

namespace App\Filament\Resources\InformacionExogenaResource\Pages;

use App\Filament\Resources\InformacionExogenaResource;
use Filament\Resources\Pages\Page;

class ViewInformacionExogena extends Page
{
    protected static string $resource = InformacionExogenaResource::class;

    protected static string $view = 'filament.resources.exogena-resource.pages.view-exogena';

}
