<?php

namespace App\Filament\Resources\OrderQuantityHistoryResource\Pages;

use App\Filament\Resources\OrderQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderQuantityHistories extends ListRecords
{
    protected static string $resource = OrderQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
