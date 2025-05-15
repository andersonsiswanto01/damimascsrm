<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Customer;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;


class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        // If stage > 1, return an empty array = no header actions allowed
        if ($this->record->order_stage_id > 1) {
            return [];
        }
    
        return [
            Actions\DeleteAction::make(),
            Action::make('Verify Documents')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->label('Verify Documents')
                ->action(function () {
                    $order = $this->record;
                    $customer = \App\Models\Customer::find($order->customer_id);
    
                    if ($order->order_stage_id > 1) {
                        Notification::make()
                            ->title('Order is already in process or completed.')
                            ->danger()
                            ->send();
    
                        return;
                    }
  
                    $ktpPhoto = $order?->KTP_photo;
                    $landCert = $order->landcertificate_photo;
                    $statementLetter = $order->statementletter_photo;
                    $kartukeluarga = $order->kartu_keluarga;
    
                    if (
                        $order->order_stage_id == 1 &&
                        $landCert &&
                        $statementLetter &&
                        $ktpPhoto &&
                        $kartukeluarga
                    ) {
                        $order->incrementOrderStage('All documents are present, applying for verification');
    
                        Notification::make()
                            ->title('Order moved to next stage!')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Not all documents are complete.')
                            ->warning()
                            ->send();
                    }
                }),
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
        // If stage is 3, return the custom action
        if ($this->record->order_stage_id == 3) {
            return [
                Action::make('submitPaymentSection')
                    ->label('Submit Payment Details')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->action('submitPaymentSection'),
            ];
        }

        // If stage is greater than 1, return no actions
        if ($this->record->order_stage_id > 1) {
            return [];
        }
        // Return actions including your custom action
        return [
            $this->getSaveFormAction(),
            Actions\DeleteAction::make(),
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

//     return $data;
// }


    
}
