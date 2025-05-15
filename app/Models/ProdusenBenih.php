<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdusenBenih extends Model
{
    protected $table = 'produsen_benih';

    protected $fillable = ['name', 'address'];

    public function products()
    {
        return $this->hasMany(ProdusenBenihProduct::class);
    }

    public function sp2bksProducts()
{
    return $this->hasManyThrough(
        \App\Models\Sp2bksProduct::class,     // Final model to retrieve
        \App\Models\ProdusenBenihProduct::class, // Intermediate model
        'produsen_benih_id',                  // Foreign key on intermediate table
        'produsen_benih_product_id',          // Foreign key on final table
        'id',                                 // Local key on ProdusenBenih
        'id'                                  // Local key on ProdusenBenihProduct
    );
}

public function getTotalQuantity()
{
    return $this->sp2bksProducts()->sum('quantity'); // Assuming 'quantity' is a field in sp2bks_products table
}

    
}
