<?php

namespace App\Filament\Resources\SalesInteractions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\SalesInteractions\SalesInteractions\SalesInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesInteractions extends ListRecords
{
    protected static string $resource = SalesInteractionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
