<?php

namespace App\Filament\Resources\DeliveryOrders\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DeliveryOrders\DeliveryOrders\DeliveryOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryOrder extends EditRecord
{
    protected static string $resource = DeliveryOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
