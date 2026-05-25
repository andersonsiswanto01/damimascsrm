<?php

namespace App\Filament\Resources\OrderDocumentVerifications\Pages;

use App\Filament\Resources\OrderDocumentVerifications\OrderDocumentVerifications\OrderDocumentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderDocumentVerification extends CreateRecord
{
    protected static string $resource = OrderDocumentVerificationResource::class;
}
