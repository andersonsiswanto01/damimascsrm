<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderDocumentStage extends Model
{
   use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'color',
        'editable',
    ];

    // Optional: orders in this stage
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
