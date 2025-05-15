<?php

namespace App\Filament\Resources\PaymentVerificationResource\Pages;

use App\Filament\Resources\PaymentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPaymentVerification extends EditRecord
{
    protected static string $resource = PaymentVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }
}
