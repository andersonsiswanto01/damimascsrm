<?php

namespace App\Filament\Resources\CorporateSp2bksResource\Pages;

use App\Filament\Resources\CorporateSp2bksResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCorporateSp2bks extends ListRecords
{
    protected static string $resource = CorporateSp2bksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
