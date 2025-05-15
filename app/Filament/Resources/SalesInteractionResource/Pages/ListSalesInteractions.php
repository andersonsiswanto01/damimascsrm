<?php

namespace App\Filament\Resources\SalesInteractionResource\Pages;

use App\Filament\Resources\SalesInteractionResource;
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
