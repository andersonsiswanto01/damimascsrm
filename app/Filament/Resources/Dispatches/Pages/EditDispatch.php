<?php

namespace App\Filament\Resources\Dispatches\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Dispatches\DispatchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDispatch extends EditRecord
{
    protected static string $resource = DispatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
