<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStage extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'code',
        'name',
        'is_final',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
