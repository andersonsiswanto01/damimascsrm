<?php

namespace App\Filament\Resources\ProdusenBenihProductResource\Pages;

use App\Filament\Resources\ProdusenBenihProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdusenBenihProduct extends EditRecord
{
    protected static string $resource = ProdusenBenihProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
