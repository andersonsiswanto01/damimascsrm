<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Notifications\Notification;
use App\Models\User; // adjust if your applicant is tied to another model
use App\Notifications\PaymentRejectedNotification;


class OrderPayment extends Model
{
     use HasFactory;

    protected $fillable = [
        'order_payment_stage_id',
        'payment_type_id',
        'amount',
        'payment_proof',
        'revised_from',
        'revised_by',
        'revised_at',
        'notes',
        'created_by',
        'created_at',
    ];

        public function orderMaster()
    {
        return $this->belongsTo(OrderMaster::class, 'order_master_id');
    }

    public function stage()
    {
        return $this->belongsTo(OrderPaymentStage::class, 'order_payment_stage_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function revisedFrom()
    {
        return $this->belongsTo(OrderPayment::class, 'revised_from');
    }

    public function revisions()
    {
        return $this->hasMany(OrderPayment::class, 'revised_from');
    }

    public function revisedBy()
    {
        return $this->belongsTo(User::class, 'revised_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Computed attribute for approved payments
    public function isApproved(): bool
    {
        return $this->stage->code === 'approved';
    }

    public function incrementOrderStage(): void
        {

            // Optional: define a maximum stage to prevent overflow
            $maxStage = 2; // change this according to your business logic

            // Increment stage only if it hasn't reached the max
            if ($this->order_payment_stage_id < $maxStage) {
                $this->order_payment_stage_id++;
                $this->save(); // persist change to database
            }
        }

     public function rejectOrderStage(): void
{

    // Increment stage only if it hasn't reached the max
    if ($this->order_payment_stage_id != 2) {
        $this->order_payment_stage_id = 3;
        $this->save(); // persist change to database

        // ✅ Notify the applicant (assuming each OrderPayment belongs to OrderMaster → Customer → User)
        $user = $this->orderMaster->user; // must be the User model, not just ID

        if ($user) {

           // also show an instant toast if the applicant is logged in Filament
            Notification::make()
                ->title('Payment Rejected')
                ->body("Your payment for Order #{$this->order_master_id} has been rejected.")
                ->danger()
                ->sendToDatabase($user);
        }
    }
}


}
