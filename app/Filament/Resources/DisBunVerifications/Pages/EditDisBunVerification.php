<?php

namespace App\Filament\Resources\DisBunVerifications\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\DisBunVerifications\DisBunVerifications\DisBunVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisBunVerification extends EditRecord
{
    protected static string $resource = DisBunVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    
}
