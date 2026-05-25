<?php

namespace App\Filament\Resources\LeadProspects\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\LeadProspects\LeadProspects\LeadProspectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadProspect extends EditRecord
{
    protected static string $resource = LeadProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
