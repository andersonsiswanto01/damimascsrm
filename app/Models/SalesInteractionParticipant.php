<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesInteractionParticipant extends Model
{
    use HasFactory;

    protected $table = 'sales_interaction_participants';

    
    protected $fillable = [
        'sales_interaction_id',
        'participant_id',
        'participant_type',
    ];


    /**
     * Get the related sales interaction.
     */
    public function salesInteraction(): BelongsTo
    {
        return $this->belongsTo(SalesInteraction::class);
    }

    /**
     * Get the participant (either a Customer or a LeadProspect).
     */
    public function participant(): MorphTo
    {
        return $this->morphTo();
    }
}
