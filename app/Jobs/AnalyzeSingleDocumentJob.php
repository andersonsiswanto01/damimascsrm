<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class AnalyzeSingleDocumentJob implements ShouldQueue
{
    use Queueable;

    use SerializesModels;

    protected $records;
    /**
     * Create a new job instance.
     */
    public function __construct(Collection $records)
    {
        $this->$records;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        dd($this->records);
    }
}
