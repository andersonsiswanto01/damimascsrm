<?php

namespace App\Filament\Resources\OrderDocumentVerifications\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\OrderDocumentVerifications\OrderDocumentVerifications\OrderDocumentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderDocumentVerification extends EditRecord
{
    protected static string $resource = OrderDocumentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
