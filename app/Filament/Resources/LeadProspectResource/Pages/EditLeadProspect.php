<?php

namespace App\Filament\Resources\LeadProspectResource\Pages;

use App\Filament\Resources\LeadProspectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadProspect extends EditRecord
{
    protected static string $resource = LeadProspectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
