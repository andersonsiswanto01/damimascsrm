<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'sequence_number',
        'month',
        'year',
        'code',
        'date',
        'note',
    ];

    // Automatically generate code on create
    protected static function booted()
    {
        static::creating(function ($po) {
            $month = now()->format('m');
            $year = now()->format('Y');

            // find last number for the current month/year
            $lastNumber = self::where('month', $month)
                ->where('year', $year)
                ->max('sequence_number') ?? 0;

            $po->sequence_number = $lastNumber + 1;
            $po->month = $month;
            $po->year = $year;

            // build code string
            $po->code = str_pad($po->sequence_number, 4, '0', STR_PAD_LEFT)
                . "/SUS/$month/$year";
        });
    }
}
