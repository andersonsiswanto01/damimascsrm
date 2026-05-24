<?php

namespace App\Filament\Resources\SalesPipelines\Pages;

use App\Filament\Resources\SalesPipelines\SalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesPipelines extends ListRecords
{
    protected static string $resource = SalesPipelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
