<?php

namespace App\Filament\Resources\SalesPipelineResource\Pages;

use App\Filament\Resources\SalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesPipeline extends EditRecord
{
    protected static string $resource = SalesPipelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
