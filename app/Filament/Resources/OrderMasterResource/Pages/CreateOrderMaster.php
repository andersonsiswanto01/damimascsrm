<?php

namespace App\Filament\Resources\OrderMasterResource\Pages;

use App\Filament\Resources\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderMaster extends CreateRecord
{
    protected static string $resource = OrderMasterResource::class;
}
