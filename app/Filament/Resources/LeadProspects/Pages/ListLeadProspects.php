<?php

namespace App\Filament\Resources\LeadProspects\Pages;

use App\Filament\Resources\LeadProspects\LeadProspectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadProspects extends ListRecords
{
    protected static string $resource = LeadProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
