<?php

namespace App\Filament\Resources\Dispatches\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispatches extends ListRecords
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
