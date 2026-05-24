<?php

namespace App\Filament\Resources\OrderDocumentVerifications\Pages;

use App\Filament\Resources\OrderDocumentVerifications\OrderDocumentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderDocumentVerifications extends ListRecords
{
    protected static string $resource = OrderDocumentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Document Verifications';
    }

}
