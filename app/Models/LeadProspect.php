<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadProspect extends Model
{
    use HasFactory;

    // Explicitly define the table name (if Laravel doesn't automatically detect it)
    protected $table = 'lead_prospects';

    // Specify fillable fields (columns that can be mass assigned)
    protected $fillable = [
        'name',  // Example columns
        'phone',
        'email',
        'event_id',
        'status',
    ];

    // Define relationships (example: connecting with the Event model)
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function salesInteractions(){
        return $this->morphMany(SalesInteractionParticipant::class, 'participant');
    }
}
