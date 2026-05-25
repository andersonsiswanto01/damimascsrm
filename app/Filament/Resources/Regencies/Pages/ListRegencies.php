<?php

namespace App\Filament\Resources\Regencies\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Regencies\Regencies\RegencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRegencies extends ListRecords
{
    protected static string $resource = RegencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
