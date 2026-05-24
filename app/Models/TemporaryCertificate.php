<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryCertificate extends Model
{
       protected $table = 'temporary_certificate';

    protected $primaryKey = 'id';

    public $incrementing = false;   // IMPORTANT

    protected $keyType = 'string';  // IMPORTANT

    public $timestamps = false; // 👈 THIS LINE

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
        // 'province_id' is the foreign key in TemporaryCertificate table
        // 'id' is the primary key in Province table
    }
    
    protected $fillable = [
        'id',
        'name',
        'segment',
        'date',
        'village',
        'district',
        'city',
        'province',
        'product'
    ];
}
