<?php

namespace App\Filament\Resources\DisBunVerifications\Pages;

use App\Filament\Resources\DisBunVerifications\DisBunVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisBunVerifications extends ListRecords
{
    protected static string $resource = DisBunVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
