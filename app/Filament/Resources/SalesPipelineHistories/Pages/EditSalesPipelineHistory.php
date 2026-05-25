<?php

namespace App\Filament\Resources\SalesPipelineHistories\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\SalesPipelineHistories\SalesPipelineHistories\SalesPipelineHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesPipelineHistory extends EditRecord
{
    protected static string $resource = SalesPipelineHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
