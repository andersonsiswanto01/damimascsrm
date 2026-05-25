<?php

namespace App\Filament\Resources\PaymentVerifications\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\PaymentVerifications\PaymentVerifications\PaymentVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentVerifications extends ListRecords
{
    protected static string $resource = PaymentVerificationResource::class;



    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Payment Verifications';
    }

    

}
