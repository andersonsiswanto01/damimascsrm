<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantityHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'previous_quantity', 'quantity_change', 'new_quantity', 'reason', 'user_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
