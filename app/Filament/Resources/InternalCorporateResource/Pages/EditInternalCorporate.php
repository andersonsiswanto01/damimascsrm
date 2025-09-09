<?php

namespace App\Filament\Resources\InternalCorporateResource\Pages;

use App\Filament\Resources\InternalCorporateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternalCorporate extends EditRecord
{
    protected static string $resource = InternalCorporateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
