<?php

namespace App\Filament\Resources\InternalCorporates\Pages;

use App\Filament\Resources\InternalCorporates\InternalCorporateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternalCorporates extends ListRecords
{
    protected static string $resource = InternalCorporateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
