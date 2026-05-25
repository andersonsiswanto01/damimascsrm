<?php

namespace App\Filament\Resources\OrderMasters\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\OrderMasters\OrderMasters\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderMasters extends ListRecords
{
    protected static string $resource = OrderMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
