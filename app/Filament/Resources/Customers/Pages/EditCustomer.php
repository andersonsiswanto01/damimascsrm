<?php

namespace App\Filament\Resources\Customers\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use App\Filament\Resources\Customers\Customers\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use App\Models\Customer;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
{
    $data = $this->form->getState();

    // Check for duplicates (excluding the current record)
    $duplicate = Customer::where('telephone_number', $data['telephone_number'])
        ->where('id', '!=', $this->record->id)
        ->exists();

    if ($duplicate) {
        Notification::make()
            ->title('Duplicate Phone Number')
            ->body('The phone number is already used by another customer.')
            ->danger()
            ->send();

        // 💥 Stop the save process
        $this->halt();
    }
}
}
