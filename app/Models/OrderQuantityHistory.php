<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderQuantityHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_product_id',
        'allocated_date',
        'allocated_quantity',
        'remaining_balance',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}