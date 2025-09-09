<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class OrderMaster extends Model
{
    protected $fillable = [
        'reference_number',
        'order_date',
        'customer_id',
        'notes',
        'payment_date',
        'payment_picture', // for storing multiple image filenames/paths
        'order_source',
        'po_number',
            // other fields...
    ];

    protected $casts = [
        'payment_picture' => 'array',
    ];
    
    protected static function boot()
{
    parent::boot();

    static::saving(function ($order) {

        if (auth()->check() && is_null($order->user_id)) {
            $order->user_id = auth()->id();
        }
       
    });
}

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'order_master_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gettotalPurchaseAttribute()
    {
        return function (){
            $this->loadMissing('orders.orderProducts.product');

            return $this->orders->sum(function ($order) {
                return $order->orderProducts->sum(function ($orderProduct) {
                    $price = $orderProduct->product_price ?? 0;
                    return $orderProduct->qty * $price;
                });
            });


        };
    }

public function incrementOrderStage(): void
{
    $orders = Order::where('order_master_id', $this->id)->get();

    foreach ($orders as $order) {
        if ($order->order_stage_id == 3) {
            $order->incrementOrderStage('Payment Verified');
        }
    }
}

    public function getAllOrdersHaveStageGreaterThanTwoAttribute()
{
    // Check if all related orders have order_stage > 2
    return $this->orders->every(fn ($order) => $order->order_stage_id > 2);
}

public function getInvoiceStatementAttribute()
{

    return match ($this->order_source) {
      'whatsapp' => 'diskusi melalui WhatsApp dengan',
        'meeting' => ' pertemuan langsung dengan',
        'other' => ' diskusi sebelumnya dengan',
        'po' => $this->po_number
            ? ' Purchase Order dari perusahaan penerbit dengan nomor PO ' . $this->po_number . ' dengan'
            : ' Purchase Order dari perusahaan penerbit dengan',
        default => ' kesepakatan sebelumnya dengan',
    };
}

   

}
