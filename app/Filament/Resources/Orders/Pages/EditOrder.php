<?php

namespace App\Filament\Resources\Orders\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use App\Filament\Resources\Orders\Orders\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Customer;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;


class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function resolveRecord($key): Model
{
    return static::getModel()::with([
        'orderMaster.customer', // 👈 preload the customer relationship
        // 'customer.address', // you can even chain deeper
        // 'products', // preload other relationships too
    ])->findOrFail($key);
}

    protected function getHeaderActions(): array
    {

        return [
            DeleteAction::make(),
             Action::make('Verify Documents')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->label('Verify Documents')
            ->action(function () {
                // your logic here
            })
            ->visible(fn (): bool =>
                $this->record->orderDocumentStage->code === 'waiting_docs'
                || $this->record->orderDocumentStage->code === 'document_revision'
            ),
        ];
    }

    public function submitPaymentSection()
    {
        // Initialize paymentPhoto as null
        $paymentPhoto = null;

        // Check if payment_photo exists and is not empty
        if (isset($this->data['payment_photo']) && !empty($this->data['payment_photo'])) {
            // Access the first file in the array
            $file = reset($this->data['payment_photo']);  // Using reset() to get the first file in the array

            // Generate a custom file name or use the original name
            $fileName = uniqid('payment_') . '.' . $file->getClientOriginalExtension();  // You can customize this as needed

            // Store the file in 'private/documents' directory
            $paymentPhoto = $file->storeAs('private/documents', $fileName, 'local');  // Save the file with the new name

        }

        // Get payment_date from the form data
        $paymentDate = $this->data['payment_date'];

        // Update the record with the file path and payment date
        $this->record->update([
            'payment_photo' => $paymentPhoto,  // Save the relative file path (e.g., 'private/documents/xxxxx.jpg')
            'payment_date' => $paymentDate,    // Save the date
        ]);

        // Send success notification
        Notification::make()
            ->title('Payment details submitted successfully!')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
{
    $this->record->loadMissing('orderDocumentStage');

    if ($this->record->orderDocumentStage?->editable) {
        // editable = 1 → allow edit
        return [
            $this->getSaveFormAction(),
            DeleteAction::make(),
        ];
    }

    // editable = 0 → lock
    return [

    ];
}


//     protected function mutateFormDataBeforeSave(array $data): array
// {
//     $order = $this->record; // Accessing the current order record (the one being edited)
//     $customer = \App\Models\Customer::find($data['customer_id'] ?? null);
//     $orderId = $this->record->id ;
//     $orderStage = $this->record->order_stage_id;

//     $ktpPhoto = $customer?->KTP_photo; // Note: should match your actual column name (usually lowercase with underscores)
//     $landCert = $data['landcertificate_photo'] ?? null;
//     $statementLetter = $data['statementletter_photo'] ?? null;

//       // Only change order_stage_id if it's currently 1 and all photos are present
//       if (
//         $orderStage == 1 &&
//         $landCert &&
//         $statementLetter &&
//         $ktpPhoto
//     ) {
//         $order->incrementOrderStage('All documents are present, applying for verification');
//     }

}


    
