<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesPipeline extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'customer_id', 'current_stage_id','assigned_to', 'deal_value','notes', 'status', 'expected_close'];

    public const STATUSES = [
        'open' => 'Open',
        'in_progress' => 'In Progress',
        'won' => 'Won',
        'lost' => 'Lost',
    ];

    public function lead() {
        return $this->belongsTo(LeadProspect::class);
    }
    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function history() {
        return $this->hasMany(SalesPipelineHistory::class);
    }

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function salesStage()
    {
        return $this->belongsTo(SalesStage::class, 'current_stage_id'); 
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(SalesInteraction::class);
    }
}
