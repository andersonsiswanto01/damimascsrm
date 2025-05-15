<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ Correct import

class Customer extends Model
{

    use HasFactory;
    
    public $timestamps = false; // Disables both `created_at` and `updated_at`
    public $incrementing = false; // Since we manually set the ID
    protected $keyType = 'string'; // Ensure ID is treated as a string
    
    protected $fillable = [
        'id', 'customer_name', 'company_name', 'organization_name',
        'npwp', 'nik_ktp', 'registration_date', 'address', 'status',
        'legal_form', 'phone_code', 'telephone_number', 'district_id',
        'village_id', 'regency_id', 'province_id',
        'NPWP_photo', 'SP2BKS_photo', 'SP2BKS_id', 'SP2BKS_expired', 'KTP_photo'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            // Generate the custom ID
            $datePart = Carbon::now()->format('ymd'); // YYMMDD format
            $lastCustomer = DB::table('customers')
                ->where('id', 'like', "{$datePart}%")
                ->orderBy('id', 'desc')
                ->first();

            // Extract the last used 'XXX' number and increment it
            $nextNumber = $lastCustomer
                ? (intval(substr($lastCustomer->id, -3)) + 1)
                : 0;

            // Ensure the number is always 3 digits (e.g., 001, 002, ..., 999)
            $customer->id = $datePart . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // Automatically set registered_date
            $customer->registration_date = Carbon::now();
        });
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function salesPipelines()
    {
        return $this->hasMany(SalesPipeline::class);
    }

    public function interactions()
    {
        return $this->hasMany(SalesInteraction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function salesInteractions()
{
    return $this->morphMany(SalesInteractionParticipant::class, 'participant');
}

public function corporateSp2bks()
{
    return $this->hasMany(CorporateSp2bks::class);
}

}
