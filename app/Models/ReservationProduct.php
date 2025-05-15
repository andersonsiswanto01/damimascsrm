<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationProduct extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationProductFactory> */
    use HasFactory;

    protected $table = 'reservation_product';

    public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}


}
