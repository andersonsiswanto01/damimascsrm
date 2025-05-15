<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sp2bksProduct extends Model
{

    protected $fillable = [
        'sp2bks_id',
        'produsen_benih_product_id',
        'quantity',
    ];
    
    public function sp2bks()
{
    return $this->belongsTo(CorporateSp2bks::class, 'sp2bks_id');
}

public function produsenBenihProduct()
{
    return $this->belongsTo(ProdusenBenihProduct::class);
}

}
