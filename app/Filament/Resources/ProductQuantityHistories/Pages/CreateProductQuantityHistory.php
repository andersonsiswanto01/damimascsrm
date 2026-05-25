<?php

namespace App\Filament\Resources\ProductQuantityHistories\Pages;

use App\Filament\Resources\ProductQuantityHistories\ProductQuantityHistories\ProductQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductQuantityHistory extends CreateRecord
{
    protected static string $resource = ProductQuantityHistoryResource::class;
}
