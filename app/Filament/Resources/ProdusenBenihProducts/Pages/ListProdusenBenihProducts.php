<?php

namespace App\Filament\Resources\ProdusenBenihProducts\Pages;

use App\Filament\Resources\ProdusenBenihProducts\ProdusenBenihProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProdusenBenihProducts extends ListRecords
{
    protected static string $resource = ProdusenBenihProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
