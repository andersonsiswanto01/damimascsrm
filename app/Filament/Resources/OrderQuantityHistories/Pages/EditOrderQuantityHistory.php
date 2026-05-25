<?php

namespace App\Filament\Resources\OrderQuantityHistories\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\OrderQuantityHistories\OrderQuantityHistories\OrderQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderQuantityHistory extends EditRecord
{
    protected static string $resource = OrderQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
