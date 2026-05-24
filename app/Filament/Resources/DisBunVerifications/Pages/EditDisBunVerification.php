<?php

namespace App\Filament\Resources\DisBunVerifications\Pages;

use App\Filament\Resources\DisBunVerifications\DisBunVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisBunVerification extends EditRecord
{
    protected static string $resource = DisBunVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    
}
