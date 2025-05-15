<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorporateSp2bks extends Model

{
    protected $fillable = ['customer_id', 'sp2bks_file', 'expiry_date'];
    protected $casts = [
        'id' => 'string',
        'sp2bks_file' => 'array',
        'expiry_date' => 'date',
    ];
    public function sp2bksProducts()
    {
        return $this->hasMany(Sp2bksProduct::class, 'sp2bks_id');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
