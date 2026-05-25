<?php

namespace App\Filament\Resources\CorporateSp2bks\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\CorporateSp2bks\CorporateSp2bks\CorporateSp2bksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCorporateSp2bks extends EditRecord
{
    protected static string $resource = CorporateSp2bksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
