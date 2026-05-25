<?php

namespace App\Filament\Resources\ProdusenBenihs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ProdusenBenihs\ProdusenBenihs\ProdusenBenihResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProdusenBenihs extends ListRecords
{
    protected static string $resource = ProdusenBenihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
