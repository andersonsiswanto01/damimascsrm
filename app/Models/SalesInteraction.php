<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesInteraction extends Model
{
    use HasFactory;

    protected $table = 'sales_interactions'; // Ensure it's pointing to the correct table

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($interaction) {
            // Automatically set created_by to the currently authenticated user
            $interaction->created_by = Auth::id();
        });
    }

    protected $fillable = [
        'customer_id', // ✅ Added this
        'sales_pipeline_id',  // Foreign key
        'interaction_type',   // Call, Email, WhatsApp, etc.
        'interaction_date',   // Date and time of interaction
        'summary',            // Message or summary of discussion
        'follow_up_date',     // Optional follow-up date
        'created_by',         // User who created the interaction
    ];

    public function salesPipeline()
    {
        return $this->belongsTo(SalesPipeline::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function leadProspects()
    {
        return $this->belongsTo(LeadProspect::class, 'lead_id');
    }

    public function participants()
    {
        return $this->hasMany(SalesInteractionParticipant::class, 'sales_interaction_id');
    }

    public function customers()
    {
        return $this->morphToMany(Customer::class, 'participantable', 'sales_interaction_participant', 'sales_interaction_id', 'participant_id')
            ->where('participant_type', Customer::class);
    }

    public function leadProspect()
    {
        return $this->morphToMany(LeadProspect::class, 'participantable', 'sales_interaction_participant', 'sales_interaction_id', 'participant_id')
            ->where('participant_type', LeadProspect::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(SalesInteractionFollowup::class, 'sales_interaction_id');
    }
}
