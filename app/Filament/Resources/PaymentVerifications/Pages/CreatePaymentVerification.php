<?php

namespace App\Filament\Resources\PaymentVerifications\Pages;

use App\Filament\Resources\PaymentVerifications\PaymentVerifications\PaymentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentVerification extends CreateRecord
{
    protected static string $resource = PaymentVerificationResource::class;
}
