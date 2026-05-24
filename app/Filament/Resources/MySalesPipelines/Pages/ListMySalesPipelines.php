<?php

namespace App\Filament\Resources\MySalesPipelines\Pages;

use App\Filament\Resources\MySalesPipelines\MySalesPipelineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMySalesPipelines extends ListRecords
{
    protected static string $resource = MySalesPipelineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
