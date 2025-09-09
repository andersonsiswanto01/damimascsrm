<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentResponse extends Model
{
     use HasFactory;

      protected $fillable = [
        'order_id',
        'data_type',
        'extracted_name',
        'extracted_nik',
        'extracted_address',
        'extracted_land_address',
        'extracted_title',
        'extracted_origin_skt',
        'extracted_land_size',
        'extracted_qty',
        'extracted_religion',
        'extracted_gender',
        'extracted_job',
        'extracted_marriage_status',
        'extracted_signature',
        'extracted_birth',
        'extracted_birth_place',
        'extracted_date',
        'status',
        'entity_map',
        'response',
    ];

    protected $casts = [
        'entity_map' => 'array',
        'response' => 'array',
        'extracted_birth' => 'date',
        'extracted_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
