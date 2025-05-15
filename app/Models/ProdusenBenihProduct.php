<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdusenBenihProduct extends Model
{
    protected $table = 'produsen_benih_product';

    protected $fillable = ['produsen_benih_id', 'product_name', 'description', 'price'];

    public function produsen()
    {
        return $this->belongsTo(ProdusenBenih::class, 'produsen_benih_id');
    }
    
    public function sp2bksProducts()
{
    return $this->hasMany(Sp2bksProduct::class, 'produsen_benih_product_id');
}

}
