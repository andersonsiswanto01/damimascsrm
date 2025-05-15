<?php

namespace App\Filament\Resources\ProdusenBenihResource\Pages;

use App\Filament\Resources\ProdusenBenihResource;
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
