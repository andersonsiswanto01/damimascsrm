<?php

namespace App\Filament\Resources\DisBunVerificationResource\Pages;

use App\Filament\Resources\DisBunVerificationResource;
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
