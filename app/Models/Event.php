<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    public $incrementing = false; // Disable auto-increment for 'id'
    protected $keyType = 'string'; // Ensure 'id' is treated as a string


    protected $fillable = [
        'id',
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $datePart = date('ymd', strtotime($event->start_date));

            // Find the latest ID with the same date pattern
            $latestEvent = DB::table('events')
                ->where('id', 'like', $datePart . '%')
                ->orderBy('id', 'desc')
                ->first();

            // Extract and increment sequence
            $sequence = 1;
            if ($latestEvent && preg_match('/\d{6}(\d{2})$/', $latestEvent->id, $matches)) {
                $sequence = intval($matches[1]) + 1;
            }

            // Ensure sequence is two digits (01-99)
            $event->id = $datePart . str_pad($sequence, 2, '0', STR_PAD_LEFT);
        });
    }

    // Relationship: An event has many lead prospects
    public function leadProspects()
    {
        return $this->hasMany(LeadProspect::class, 'event_id');
    }

    public function user()
{
    return $this->belongsTo(User::class);
}
}
