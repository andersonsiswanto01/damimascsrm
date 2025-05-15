<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function regency()
    {
        return $this->hasOneThrough(
            Regency::class,
            District::class,
            'id',       // Foreign key in districts
            'id',       // Foreign key in regencies
            'district_id', // Local key in villages
            'regency_id'  // Local key in districts
        );
    }

    public function province()
    {
        return $this->hasOneThrough(
            Province::class,
            Regency::class,
            'id',       // Foreign key in regencies
            'id',       // Foreign key in provinces
            'regency_id', // Local key in districts (through regency)
            'province_id' // Local key in regencies
        );
    }
}
