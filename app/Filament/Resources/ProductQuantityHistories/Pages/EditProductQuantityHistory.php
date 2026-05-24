<?php

namespace App\Filament\Resources\ProductQuantityHistories\Pages;

use App\Filament\Resources\ProductQuantityHistories\ProductQuantityHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductQuantityHistory extends EditRecord
{
    protected static string $resource = ProductQuantityHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
