<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SalesInteractionFollowup extends Model
{
    protected $fillable = [
        'sales_interaction_id', // Foreign key to SalesInteraction
        'followup_date',        // When the follow-up is scheduled
        'note',                // Any notes related to the follow-up
        'status',               // Pending, Completed, Canceled
        'created_by',           // User who created the follow-up
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id(); // Automatically set created_by
        });
    }

    /**
     * Relationship: Belongs to a Sales Interaction
     */
    public function salesInteraction()
    {
        return $this->belongsTo(SalesInteraction::class);
    }

    /**
     * Relationship: Belongs to a User (Creator)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
