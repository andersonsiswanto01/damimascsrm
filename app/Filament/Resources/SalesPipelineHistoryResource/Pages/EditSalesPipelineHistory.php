<?php

namespace App\Filament\Resources\SalesPipelineHistoryResource\Pages;

use App\Filament\Resources\SalesPipelineHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesPipelineHistory extends EditRecord
{
    protected static string $resource = SalesPipelineHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
