<?php

namespace App\Filament\Resources\LeadProspects\Pages;

use App\Filament\Resources\LeadProspects\LeadProspects\LeadProspectResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLeadProspect extends CreateRecord
{
    protected static string $resource = LeadProspectResource::class;
}
