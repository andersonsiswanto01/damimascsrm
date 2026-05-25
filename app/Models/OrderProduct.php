<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['order_id', 'product_id', 'qty','product_price', 'free_allowance', 'free_allowance_percentage'];

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

            static::saving(function ($orderProduct) {

                $percent = $orderProduct->free_allowance_percentage ?? 0;
                $orderProduct->free_allowance = round($orderProduct->qty * ($percent / 100));

            });
            
            static::created(function ($orderProduct) {
                // ✅ Automatically insert into order_quantity_history after record exists
                OrderQuantityHistory::create([
                    'order_product_id'   => $orderProduct->id,
                    'allocated_date'     => now(),
                    'allocated_quantity' => $orderProduct->qty,
                    'remaining_balance'  => $orderProduct->qty,
                ]);
            });
        }
}
