<?php

namespace App\Filament\Resources\SalesPipelineHistories\Pages;

use App\Filament\Resources\SalesPipelineHistories\SalesPipelineHistories\SalesPipelineHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesPipelineHistory extends CreateRecord
{
    protected static string $resource = SalesPipelineHistoryResource::class;
}
