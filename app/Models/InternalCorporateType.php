<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCorporateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'id'
    ];

    /**
     * Get all internal corporates that use this type.
     */
    public function internalCorporates()
    {
        return $this->hasMany(InternalCorporate::class, 'legal_form_id');
    }
}
