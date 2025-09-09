<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCorporate extends Model
{
    use HasFactory;

    protected $fillable = [
        'psm',
        'legal_form',
        'initial',
        'pt_name',
        'name',
        'province_id',
        'internal_corporate_type_id',

    ];

    /**
     * Get the legal form (type) associated with this corporate.
     */

     public function ineternalcorporateType()
    {
        return $this->belongsTo(InternalCorporateType::class, 'internal_corporate_type_id');
    }
    /**
     * Get the province associated with this corporate.
     */

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function customers()
{
    return $this->hasMany(Customer::class, 'internal_corporates_id');
}


}
