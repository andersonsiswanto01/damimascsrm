<?php

namespace App\Filament\Resources\ProductQuantityHistoryResource\Pages;

use App\Filament\Resources\ProductQuantityHistoryResource;
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
