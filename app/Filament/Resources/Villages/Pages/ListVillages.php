<?php

namespace App\Filament\Resources\Villages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Villages\Villages\VillageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVillages extends ListRecords
{
    protected static string $resource = VillageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
