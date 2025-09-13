<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderProduct extends Model
{
     protected $fillable = [
        'purchase_order_id',
        'product_id',
        'qty',
        'free_allowance_qty',
        'price',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ✅ Subtotal including only paid qty
    public function getSubtotalAttribute(): float
    {
        return $this->qty * $this->price;
    }

    // ✅ Total qty including free allowance
    public function getTotalQtyAttribute(): int
    {
        return $this->qty + $this->free_allowance_qty;
    }
}
