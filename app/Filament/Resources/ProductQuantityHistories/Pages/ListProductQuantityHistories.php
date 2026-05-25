<?php

namespace App\Filament\Resources\ProductQuantityHistories\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\ProductQuantityHistories\ProductQuantityHistories\ProductQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductQuantityHistories extends ListRecords
{
    protected static string $resource = ProductQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
