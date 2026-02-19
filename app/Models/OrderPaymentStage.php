<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderPaymentStage extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'is_editable',
    ];

    // Optional: payments in this stage
    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }
}
