<?php

namespace App\Filament\Resources\ProdusenBenihs\Pages;

use App\Filament\Resources\ProdusenBenihs\ProdusenBenihResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdusenBenih extends EditRecord
{
    protected static string $resource = ProdusenBenihResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
