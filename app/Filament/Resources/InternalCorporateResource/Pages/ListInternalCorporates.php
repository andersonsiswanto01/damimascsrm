<?php

namespace App\Filament\Resources\InternalCorporateResource\Pages;

use App\Filament\Resources\InternalCorporateResource;
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
