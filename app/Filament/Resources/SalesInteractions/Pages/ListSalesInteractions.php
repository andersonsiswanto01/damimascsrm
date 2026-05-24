<?php

namespace App\Filament\Resources\SalesInteractions\Pages;

use App\Filament\Resources\SalesInteractions\SalesInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesInteractions extends ListRecords
{
    protected static string $resource = SalesInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
