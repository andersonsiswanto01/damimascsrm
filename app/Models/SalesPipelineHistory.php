<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesPipelineHistory extends Model
{
    use HasFactory;

    protected $fillable = ['sales_pipeline_id', 'previous_status', 'new_status', 'updated_by'];

    public function salesPipeline() {
        return $this->belongsTo(SalesPipeline::class);
    }

    public function updatedBy() {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
