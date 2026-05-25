<?php

namespace App\Filament\Resources\OrderMasters\Pages;

use App\Filament\Resources\OrderMasters\OrderMasters\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderMaster extends CreateRecord
{
    protected static string $resource = OrderMasterResource::class;
}
