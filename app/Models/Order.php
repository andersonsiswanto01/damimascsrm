<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    // protected static function boot() {
    //     parent::boot();
    
    //     // Automatically assign the authenticated user when creating an order
    //     static::creating(function ($order) {
    //         if (auth()->check()) {
    //             $order->user_id = auth()->id();
    //         }
    //     });
    // }
    
    protected static function boot()
{
    parent::boot();

    static::saving(function ($order) {

        // Check if the order is being created or updated
        $village = \App\Models\Village::find($order->village_id);
        $district = \App\Models\District::find($order->district_id);
        $regency = \App\Models\Regency::find($order->regency_id);
        $province = \App\Models\Province::find($order->province_id);

        // Basic existence check
        if (!$village || !$district || !$regency || !$province) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'village_id' => 'Invalid region data selected.',
            ]);
        }

        // Relationship chain check
        if (
            $village->district_id != $district->id ||
            $district->regency_id != $regency->id ||
            $regency->province_id != $province->id
        ) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'village_id' => 'The selected region hierarchy is invalid.',
            ]);
        }
    });
}


    protected $fillable = [
         'notes', 'delivery_date','address', 'landcertificate_photo', 'statementletter_photo', 'status',
        'province_id', 'regency_id', 'district_id', 'village_id', 'order_stage_id', 'customer_name','order_master_id', 'KTP_photo', 'kartu_keluarga'
    ];

    protected $casts = [
        'landcertificate_photo' => 'array', // or 'attachments', etc.
        'KTP_photo' => 'array',
        'kartu_keluarga' => 'array',
        'statementletter_photo' => 'array',
    ];
    
//     public function generateSnapshot()
// {
//     $snapshot = [
//         'customer' => $this->customer->only(['name', 'address', 'phone']),
//         'items' => $this->items->map(function ($item) {
//             return [
//                 'product' => $item->product->name,
//                 'quantity' => $item->quantity,
//                 'price' => $item->price,
//                 'subtotal' => $item->quantity * $item->price,
//             ];
//         }),
//         'total' => $this->items->sum(fn ($item) => $item->quantity * $item->price),
//         'notes' => $this->notes,
//     ];

//     return Document::create([
//         'document_id' => 'DOC-' . now()->format('Ymd') . '-' . str_pad(Document::count() + 1, 4, '0', STR_PAD_LEFT),
//         'order_id' => $this->id,
//         'snapshot_data' => $snapshot,
//     ]);
// }

    // Relationships
   
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }
    
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    
    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }
    
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

        public function stage()
    {
        return $this->belongsTo(OrderStage::class, 'order_stage_id');
    }

    public function order_stage_histories()
{
    return $this->hasMany(OrderStatusHistory::class);
}

public function orderMaster()
{
    return $this->belongsTo(OrderMaster::class, 'order_master_id');
}


public function incrementOrderStage(string $notes = null): void
{
    $maxStageId = OrderStage::max('id');

    if ($this->order_stage_id >= $maxStageId) {
        throw new \InvalidArgumentException("Order is already at the final stage.");
    }

    $this->order_stage_id++;
    $this->save();

    $this->order_stage_histories()->create([
        'order_id' => $this->id,
        'order_stage_id' => $this->order_stage_id,
        'note' => $notes,
        'changed_by' => Auth::id(),
    ]);
}
    
public function decrementOrderStage(string $notes = null): void
{
    $minStageId = OrderStage::min('id');

    if ($this->order_stage_id <= $minStageId) {
        throw new \InvalidArgumentException("Order is already at the initial stage.");
    }

    $this->order_stage_id--;
    $this->save();

    $this->order_stage_histories()->create([
        'order_id' => $this->id,
        'order_stage_id' => $this->order_stage_id,
        'note' => $notes,
        'changed_by' => Auth::id(),
    ]);
}


    
}
