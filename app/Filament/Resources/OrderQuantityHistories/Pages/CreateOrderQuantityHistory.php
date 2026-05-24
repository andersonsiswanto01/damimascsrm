<?php

namespace App\Filament\Resources\OrderQuantityHistories\Pages;

use App\Filament\Resources\OrderQuantityHistories\OrderQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderQuantityHistory extends CreateRecord
{
    protected static string $resource = OrderQuantityHistoryResource::class;
}
