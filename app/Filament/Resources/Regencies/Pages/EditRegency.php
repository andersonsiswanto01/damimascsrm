<?php

namespace App\Filament\Resources\Regencies\Pages;

use App\Filament\Resources\Regencies\RegencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegency extends EditRecord
{
    protected static string $resource = RegencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
