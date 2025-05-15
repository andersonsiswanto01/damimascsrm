<?php

namespace App\Filament\Resources\ProdusenBenihResource\Pages;

use App\Filament\Resources\ProdusenBenihResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProdusenBenihs extends ListRecords
{
    protected static string $resource = ProdusenBenihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
