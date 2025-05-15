<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDelay extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'reason', 'details', 'new_delivery_date', 'handled_by'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}