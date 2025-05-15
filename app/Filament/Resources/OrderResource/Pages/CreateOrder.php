<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderProduct;
use App\Models\Order;
class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    // protected function handleRecordCreation(array $data): Order
    // {
    //     // Create the order
    //     $order = Order::create($data);

    //     // Check and update stage if needed
    //     if ($order->order_stage_id == 1) {
    //         $customer = Customer::find($order->customer_id);

    //          // If all docs are available, increment stage and log it
    //         if (
    //             $order->order_stage_id == 1 &&
    //             $landCert &&
    //             $statementLetter &&
    //             $ktpPhoto
    //         ) {
    //             $order->incrementOrderStage('All documents are present, applying for verification');
    //         }
    //     }

    //     return $order;
    // }
    
}