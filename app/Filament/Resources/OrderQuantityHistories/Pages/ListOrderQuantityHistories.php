<?php

namespace App\Filament\Resources\OrderQuantityHistories\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\OrderQuantityHistories\OrderQuantityHistories\OrderQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderQuantityHistories extends ListRecords
{
    protected static string $resource = OrderQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
