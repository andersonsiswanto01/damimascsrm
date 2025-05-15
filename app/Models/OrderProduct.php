<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['order_id', 'product_id', 'qty','product_price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function booted()
    {
        static::created(function ($orderProduct) {
            // ✅ Automatically insert into order_quantity_history
            \App\Models\OrderQuantityHistory::create([
               'order_product_id' => $orderProduct->id,
                'allocated_date' => now(), // Set current date for allocation
                'allocated_quantity' => $orderProduct->qty, // Allocate full quantity
                'remaining_balance' => $orderProduct->qty, // Initially, remaining balance is full quantity
            ]);
        });
    }
}
