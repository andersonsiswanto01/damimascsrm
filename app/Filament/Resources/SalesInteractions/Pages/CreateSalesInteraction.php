<?php

namespace App\Filament\Resources\SalesInteractions\Pages;

use App\Filament\Resources\SalesInteractions\SalesInteractions\SalesInteractionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalesInteraction extends CreateRecord
{
    protected static string $resource = SalesInteractionResource::class;
}
