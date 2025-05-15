<?php

namespace App\Filament\Resources\OrderQuantityHistoryResource\Pages;

use App\Filament\Resources\OrderQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderQuantityHistory extends EditRecord
{
    protected static string $resource = OrderQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
