<?php

namespace App\Filament\Resources\ProductQuantityHistoryResource\Pages;

use App\Filament\Resources\ProductQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductQuantityHistories extends ListRecords
{
    protected static string $resource = ProductQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
