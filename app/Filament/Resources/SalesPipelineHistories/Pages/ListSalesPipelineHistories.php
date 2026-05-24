<?php

namespace App\Filament\Resources\SalesPipelineHistories\Pages;

use App\Filament\Resources\SalesPipelineHistories\SalesPipelineHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesPipelineHistories extends ListRecords
{
    protected static string $resource = SalesPipelineHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
