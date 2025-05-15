<?php

namespace App\Filament\Resources\OrderMasterResource\Pages;

use App\Filament\Resources\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderMasters extends ListRecords
{
    protected static string $resource = OrderMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
