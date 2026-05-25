<?php

namespace App\Filament\Resources\DisBunVerifications\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\DisBunVerifications\DisBunVerifications\DisBunVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisBunVerifications extends ListRecords
{
    protected static string $resource = DisBunVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
