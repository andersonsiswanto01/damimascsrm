<?php

namespace App\Filament\Resources\OrderMasterResource\Pages;

use App\Filament\Resources\OrderMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderMaster extends EditRecord
{
    protected static string $resource = OrderMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
