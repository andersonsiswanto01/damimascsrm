<?php

namespace App\Filament\Resources\LeadProspects\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\LeadProspects\LeadProspects\LeadProspectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadProspects extends ListRecords
{
    protected static string $resource = LeadProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
