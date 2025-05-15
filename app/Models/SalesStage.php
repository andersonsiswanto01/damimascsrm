<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesStage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'current_stage_id', 'stage_order', 'is_final'];

    protected $table = 'sales_stages';  

}
