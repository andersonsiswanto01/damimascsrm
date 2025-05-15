<?php

namespace App\Filament\Resources\OrderDocumentVerificationResource\Pages;

use App\Filament\Resources\OrderDocumentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderDocumentVerification extends EditRecord
{
    protected static string $resource = OrderDocumentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
