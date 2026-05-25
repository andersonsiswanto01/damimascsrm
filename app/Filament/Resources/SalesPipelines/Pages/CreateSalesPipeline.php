<?php

namespace App\Filament\Resources\SalesPipelines\Pages;

use App\Filament\Resources\SalesPipelines\SalesPipelines\SalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesPipeline extends CreateRecord
{
    protected static string $resource = SalesPipelineResource::class;
}
