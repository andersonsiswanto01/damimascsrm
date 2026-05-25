<?php

namespace App\Filament\Resources\Regencies\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Regencies\Regencies\RegencyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRegency extends EditRecord
{
    protected static string $resource = RegencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
