<?php

namespace App\Filament\Resources\PaymentVerificationResource\Pages;

use App\Filament\Resources\PaymentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentVerifications extends ListRecords
{
    protected static string $resource = PaymentVerificationResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Payment Verifications';
    }

    

}
