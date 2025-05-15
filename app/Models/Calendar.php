<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use HasFactory;

    protected $table = 'calendar';

    protected $fillable = [
        'id',
        'title',
        'description',
        'start_time',
        'end_time',
    ];

    public function id($id): static
    {
        $this->id = $id;
        return $this;
    }

    public function title($title): static
    {
        $this->title = $title;
        return $this;
    }

    public function start($start): static
    {
        $this->start = $start;
        return $this;
    }

    public function end($end): static
    {
        $this->end = $end;
        return $this;
    }

    public function description($description): static
    {
        $this->description = $description;
        return $this;
    }

    
}
