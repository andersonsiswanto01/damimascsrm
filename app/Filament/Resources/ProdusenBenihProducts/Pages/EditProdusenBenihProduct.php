<?php

namespace App\Filament\Resources\ProdusenBenihProducts\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\ProdusenBenihProducts\ProdusenBenihProducts\ProdusenBenihProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdusenBenihProduct extends EditRecord
{
    protected static string $resource = ProdusenBenihProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
