<?php

namespace App\Filament\Resources\Customers\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Customers\Customers\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
